<?php

namespace App\Modules\Programme\Livewire;

use App\Modules\Programme\Enums\EventStatus;
use App\Modules\Programme\Models\Event;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class EventIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url(as: 'status')]
    public string $statusFilter = '';

    #[Url(as: 'type')]
    public string $typeFilter = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedTypeFilter(): void
    {
        $this->resetPage();
    }

    public function submit(int $id): void
    {
        $event = $this->find($id);
        Gate::authorize('update', $event);

        if ($event->status !== EventStatus::Draft) {
            $this->flash('error', 'Only a draft can be submitted for approval.');

            return;
        }

        $event->forceFill(['status' => EventStatus::Submitted])->save();
        $this->flash('status', $event->title.' submitted for approval.');
    }

    public function approve(int $id): void
    {
        $event = $this->find($id);
        Gate::authorize('approve', $event);

        if ($event->status !== EventStatus::Submitted) {
            $this->flash('error', 'Only a submitted event can be approved.');

            return;
        }

        $event->forceFill([
            'status' => EventStatus::Approved,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ])->save();

        $this->flash('status', $event->title.' approved. Publish it when the listing is ready.');
    }

    public function publish(int $id): void
    {
        $event = $this->find($id);
        Gate::authorize('publish', $event);

        $missing = collect([
            'title' => 'a title',
            'starts_at' => 'a start date',
            'summary' => 'a short summary',
            'venue_name' => 'a venue',
        ])->filter(fn (string $label, string $field): bool => blank($event->{$field}))
            ->values()
            ->implode(', ');

        if ($missing !== '') {
            $this->flash('error', 'Add '.$missing.' before publishing.');

            return;
        }

        $event->forceFill([
            'status' => EventStatus::Published,
            'published_at' => $event->published_at ?? now(),
        ])->save();

        $this->flash('status', $event->title.' is live on the public site.');
    }

    public function unpublish(int $id): void
    {
        $event = $this->find($id);
        Gate::authorize('update', $event);

        $event->forceFill([
            'status' => EventStatus::Draft,
            'published_at' => null,
            'approved_at' => null,
            'approved_by' => null,
        ])->save();

        $this->flash('status', $event->title.' moved back to draft.');
    }

    public function complete(int $id): void
    {
        $event = $this->find($id);
        Gate::authorize('update', $event);

        if (! in_array($event->status, [EventStatus::Published, EventStatus::Approved], true)) {
            $this->flash('error', 'Only a published event can be marked completed.');

            return;
        }

        $event->forceFill(['status' => EventStatus::Completed])->save();
        $this->flash('status', $event->title.' marked completed.');
    }

    public function cancel(int $id): void
    {
        $event = $this->find($id);
        Gate::authorize('update', $event);

        $event->forceFill([
            'status' => EventStatus::Cancelled,
            'published_at' => null,
        ])->save();

        $this->flash('status', $event->title.' cancelled and hidden from the public site.');
    }

    public function delete(int $id): void
    {
        $event = $this->find($id);
        Gate::authorize('delete', $event);

        $event->delete();
        $this->resetPage();
        $this->flash('status', 'Event removed.');
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'statusFilter', 'typeFilter']);
        $this->resetPage();
    }

    public function render()
    {
        $events = Event::query()
            ->with(['districtAssociation', 'sport'])
            ->when($this->search !== '', function ($query): void {
                $term = '%'.$this->search.'%';
                $query->where(function ($inner) use ($term): void {
                    $inner->where('title', 'like', $term)
                        ->orWhere('venue_name', 'like', $term);
                });
            })
            ->when($this->statusFilter !== '', fn ($query) => $query->where('status', $this->statusFilter))
            ->when($this->typeFilter !== '', fn ($query) => $query->where('event_type', $this->typeFilter))
            ->orderByDesc('starts_at')
            ->paginate(12);

        return view('programme::livewire.events.index', [
            'events' => $events,
            'statusOptions' => EventStatus::options(),
            'typeOptions' => \App\Modules\Programme\Enums\EventType::options(),
            'counts' => [
                'draft' => Event::query()->where('status', EventStatus::Draft)->count(),
                'submitted' => Event::query()->where('status', EventStatus::Submitted)->count(),
                'published' => Event::query()->whereIn('status', [EventStatus::Published, EventStatus::Completed])->count(),
                'upcoming' => Event::query()->publiclyVisible()->upcoming()->count(),
            ],
            'canManage' => auth()->user()?->canManageAssociation() ?? false,
        ]);
    }

    private function find(int $id): Event
    {
        return Event::query()->findOrFail($id);
    }

    private function flash(string $key, string $message): void
    {
        session()->flash($key, $message);
    }
}
