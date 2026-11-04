<?php

namespace App\Modules\Programme\Livewire;

use App\Modules\Programme\Models\Event;
use App\Modules\Programme\Models\EventMedia;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class EventMediaIndex extends Component
{
    use WithFileUploads;

    public Event $event;

    public array $uploads = [];

    public ?int $editingId = null;

    public string $caption = '';

    public string $alt = '';

    public string $credit = '';

    public bool $isPublic = true;

    public function mount(Event $event): void
    {
        $this->event = $event;
        $this->authorize('uploadMedia', $event);
    }

    public function addUpload(): void
    {
        $this->uploads[] = null;
    }

    public function removeUpload(int $index): void
    {
        unset($this->uploads[$index]);
        $this->uploads = array_values($this->uploads);
    }

    public function save(): void
    {
        $this->authorize('uploadMedia', $this->event);

        $validated = $this->validate([
            'uploads' => ['array', 'max:12'],
            'uploads.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
        ], [
            'uploads.*' => 'Each file must be a JPG, PNG or WebP image up to 6 MB.',
        ]);

        $position = (int) EventMedia::query()->where('event_id', $this->event->getKey())->max('position');
        $added = 0;

        foreach ($validated['uploads'] as $file) {
            $path = $file->store('events/gallery', 'public');
            $size = @getimagesize(Storage::disk('public')->path($path));

            $position += 10;

            EventMedia::query()->create([
                'event_id' => $this->event->getKey(),
                'caption' => null,
                'alt' => $this->event->title.' — gallery photograph',
                'file_path' => $path,
                'width' => $size[0] ?? null,
                'height' => $size[1] ?? null,
                'credit' => $this->event->association?->short_name ?: $this->event->association?->name,
                'is_public' => true,
                'position' => $position,
            ]);

            $added++;
        }

        $this->uploads = [];
        session()->flash('status', $added === 1 ? 'Photograph added.' : $added.' photographs added.');
    }

    public function edit(int $mediaId): void
    {
        $media = $this->findMedia($mediaId);
        $this->authorize('update', $media);

        $this->editingId = $media->getKey();
        $this->caption = (string) $media->caption;
        $this->alt = (string) $media->alt;
        $this->credit = (string) $media->credit;
        $this->isPublic = $media->is_public;
        $this->resetErrorBag();
    }

    public function update(): void
    {
        $media = $this->findMedia($this->editingId ?? 0);
        $this->authorize('update', $media);

        $validated = $this->validate([
            'caption' => ['nullable', 'string', 'max:180'],
            'alt' => ['required', 'string', 'max:255'],
            'credit' => ['nullable', 'string', 'max:120'],
            'isPublic' => ['boolean'],
        ]);

        $media->update([
            'caption' => $this->blankToNull($validated['caption'] ?? null),
            'alt' => $validated['alt'],
            'credit' => $this->blankToNull($validated['credit'] ?? null),
            'is_public' => (bool) $this->isPublic,
        ]);

        $this->editingId = null;
        session()->flash('status', 'Photo details saved.');
    }

    public function togglePublic(int $mediaId): void
    {
        $media = $this->findMedia($mediaId);
        $this->authorize('update', $media);

        $media->forceFill(['is_public' => ! $media->is_public])->save();
    }

    public function reorder(array $orderIds): void
    {
        $this->authorize('update', $this->event);

        $ids = collect($orderIds)->map(fn (mixed $id): int => (int) $id)->all();

        $existing = EventMedia::query()
            ->where('event_id', $this->event->getKey())
            ->pluck('id')
            ->all();

        if (collect($ids)->diff($existing)->isNotEmpty() || count($ids) !== count($existing)) {
            return;
        }

        foreach (array_values($ids) as $index => $id) {
            EventMedia::query()->whereKey($id)->update(['position' => ($index + 1) * 10]);
        }
    }

    public function delete(int $mediaId): void
    {
        $media = $this->findMedia($mediaId);
        $this->authorize('delete', $media);

        if (is_string($media->file_path) && $media->file_path !== '') {
            Storage::disk('public')->delete($media->file_path);
        }

        $media->delete();
        session()->flash('status', 'Photograph removed.');
    }

    public function closeForm(): void
    {
        $this->editingId = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('programme::livewire.event-media.index', [
            'media' => EventMedia::query()
                ->where('event_id', $this->event->getKey())
                ->orderBy('position')
                ->get(),
            'publicCount' => EventMedia::query()
                ->where('event_id', $this->event->getKey())
                ->where('is_public', true)
                ->count(),
        ]);
    }

    private function findMedia(int $id): EventMedia
    {
        return EventMedia::query()->where('event_id', $this->event->getKey())->findOrFail($id);
    }

    private function blankToNull(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }
}
