<?php

namespace App\Modules\Association\Livewire;

use App\Modules\Association\Models\Association;
use App\Modules\Association\Services\AssociationSettings;
use App\Modules\Core\Association\AssociationContext;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class Profile extends Component
{
    use WithFileUploads;

    public string $name = '';

    public string $shortName = '';

    public string $email = '';

    public string $phone = '';

    public string $website = '';

    public string $instagramUrl = '';

    public string $nationalBodyUrl = '';

    public string $address = '';

    public string $about = '';

    public string $primaryColor = '#0f766e';

    public string $secondaryColor = '#f59e0b';

    public $logo = null;

    public bool $removeLogo = false;

    public ?string $existingLogoUrl = null;

    public function mount(): void
    {
        $association = app(AssociationContext::class)->require();
        $this->authorize('view', $association);

        $this->name = $association->name;
        $this->shortName = $association->short_name ?? '';
        $this->email = $association->email ?? '';
        $this->phone = $association->phone ?? '';
        $this->website = $association->website ?? '';
        $this->instagramUrl = (string) $association->setting(AssociationSettings::INSTAGRAM_URL, '');
        $this->nationalBodyUrl = (string) $association->setting(AssociationSettings::NATIONAL_BODY_URL, '');
        $this->address = $association->address ?? '';
        $this->about = (string) $association->setting(AssociationSettings::ABOUT, '');
        $this->primaryColor = $association->primaryColor();
        $this->secondaryColor = $association->secondaryColor();
        $this->existingLogoUrl = $association->logoUrl();
    }

    public function updatedLogo(): void
    {
        if ($this->logo !== null) {
            $this->removeLogo = false;
        }
    }

    public function save(AssociationSettings $settings): void
    {
        $association = app(AssociationContext::class)->require();
        $this->authorize('update', $association);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:150'],
            'shortName' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'website' => ['nullable', 'url', 'max:200'],
            'instagramUrl' => ['nullable', 'url', 'max:200'],
            'nationalBodyUrl' => ['nullable', 'url', 'max:200'],
            'address' => ['nullable', 'string', 'max:500'],
            'about' => ['nullable', 'string', 'max:2000'],
            'primaryColor' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'secondaryColor' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'removeLogo' => ['boolean'],
        ]);

        $association->update([
            'name' => $validated['name'],
            'short_name' => $validated['shortName'] ?: null,
            'email' => $validated['email'] ?: null,
            'phone' => $validated['phone'] ?: null,
            'website' => $validated['website'] ?: null,
            'address' => $validated['address'] ?: null,
        ]);

        $values = [
            AssociationSettings::ABOUT => $validated['about'] ?: null,
            AssociationSettings::PRIMARY_COLOR => strtolower($this->primaryColor),
            AssociationSettings::SECONDARY_COLOR => strtolower($this->secondaryColor),
            AssociationSettings::INSTAGRAM_URL => $validated['instagramUrl'] ?: null,
            AssociationSettings::NATIONAL_BODY_URL => $validated['nationalBodyUrl'] ?: null,
        ];

        $currentLogo = $association->setting(AssociationSettings::LOGO_PATH);

        if ($this->logo !== null) {
            $path = $this->logo->store('associations/'.$association->getKey(), 'public');

            if (is_string($currentLogo) && $currentLogo !== '') {
                Storage::disk('public')->delete($currentLogo);
            }

            $values[AssociationSettings::LOGO_PATH] = $path;
        } elseif ($this->removeLogo) {
            if (is_string($currentLogo) && $currentLogo !== '') {
                Storage::disk('public')->delete($currentLogo);
            }

            $values[AssociationSettings::LOGO_PATH] = null;
        }

        $settings->setMany($association, $values);

        $this->logo = null;
        $this->removeLogo = false;
        $this->existingLogoUrl = $association->logoUrl();

        session()->flash('status', 'Association profile updated.');
    }

    public function render()
    {
        $association = app(AssociationContext::class)->require();

        return view('association::livewire.profile', [
            'association' => $association,
            'canEdit' => auth()->user()->can('update', $association),
            'domains' => $association->domains()->orderBy('domain')->pluck('domain'),
        ]);
    }
}
