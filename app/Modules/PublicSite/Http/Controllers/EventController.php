<?php

namespace App\Modules\PublicSite\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Content\Models\ContentBlock;
use App\Modules\Core\Association\AssociationContext;
use App\Modules\Programme\Enums\EventLevel;
use App\Modules\Programme\Enums\EventType;
use App\Modules\Programme\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request, AssociationContext $context): View
    {
        $association = $context->require();

        $type = $request->query('type');
        $level = $request->query('level');
        $year = (int) $request->query('year', 0);

        $base = Event::query()->publiclyVisible();

        $upcoming = (clone $base)
            ->upcoming()
            ->when($type && EventType::tryFrom($type), fn ($query, $case) => $query->where('event_type', $case->value))
            ->when($level && EventLevel::tryFrom($level), fn ($query, $case) => $query->where('level', $case->value))
            ->with(['districtAssociation', 'sport'])
            ->orderBy('starts_at')
            ->limit(24)
            ->get();

        $past = (clone $base)
            ->past()
            ->when($type && EventType::tryFrom($type), fn ($query, $case) => $query->where('event_type', $case->value))
            ->when($level && EventLevel::tryFrom($level), fn ($query, $case) => $query->where('level', $case->value))
            ->when($year > 0, fn ($query) => $query->whereYear('starts_at', $year))
            ->with(['districtAssociation', 'sport'])
            ->orderByDesc('starts_at')
            ->limit(30)
            ->get();

        $years = Event::query()
            ->publiclyVisible()
            ->selectRaw('DISTINCT YEAR(starts_at) as year')
            ->orderByDesc('year')
            ->pluck('year')
            ->map(fn (mixed $value): int => (int) $value)
            ->all();

        return view('public.events.index', [
            'association' => $association,
            'upcoming' => $upcoming,
            'past' => $past,
            'years' => $years,
            'typeOptions' => EventType::options(),
            'levelOptions' => EventLevel::options(),
            'activeType' => $type,
            'activeLevel' => $level,
            'activeYear' => $year > 0 ? $year : null,
            'blocks' => ContentBlock::query()->active()->forPage('events')->get(),
        ]);
    }

    public function show(AssociationContext $context, Event $event): View
    {
        abort_unless($event->isPubliclyVisible(), 404);

        $association = $context->require();

        $related = Event::query()
            ->publiclyVisible()
            ->whereKeyNot($event->getKey())
            ->orderByDesc('starts_at')
            ->limit(3)
            ->get();

        $entries = $event->entries()
            ->with(['player', 'category', 'districtAssociation'])
            ->where('status', '!=', \App\Modules\Programme\Enums\EventEntryStatus::Awaiting->value)
            ->orderByRaw('placement is null, placement asc')
            ->limit(60)
            ->get();

        $media = $event->publicMedia()->limit(24)->get();

        return view('public.events.show', [
            'association' => $association,
            'event' => $event,
            'related' => $related,
            'entries' => $entries,
            'media' => $media,
            'blocks' => ContentBlock::query()->active()->forPage('events')->get(),
        ]);
    }
}
