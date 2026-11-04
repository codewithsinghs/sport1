<?php

namespace App\Modules\Association\Services;

use App\Modules\Association\Models\Association;
use App\Modules\Association\Models\AssociationSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AssociationSettings
{
    public const ABOUT = 'about';

    public const PRIMARY_COLOR = 'primary_color';

    public const SECONDARY_COLOR = 'secondary_color';

    public const LOGO_PATH = 'logo_path';

    public const INSTAGRAM_URL = 'instagram_url';

    public const NATIONAL_BODY_URL = 'national_body_url';

    public function all(Association $association): array
    {
        $associationId = $association->getKey();

        $settings = Cache::remember(
            $this->cacheKey($associationId),
            now()->addMinutes(30),
            fn (): array => AssociationSetting::query()
                ->where('association_id', $associationId)
                ->get()
                ->mapWithKeys(fn (AssociationSetting $setting): array => [
                    $setting->key => $setting->value,
                ])
                ->all()
        );

        return array_merge($this->defaults(), $settings);
    }

    public function get(Association $association, string $key, mixed $default = null): mixed
    {
        return data_get($this->all($association), $key, $default);
    }

    public function setMany(Association $association, array $values): void
    {
        DB::transaction(function () use ($association, $values): void {
            foreach ($values as $key => $value) {
                AssociationSetting::query()->updateOrCreate(
                    [
                        'association_id' => $association->getKey(),
                        'key' => $key,
                    ],
                    ['value' => $value]
                );
            }
        });

        $this->forget($association);
    }

    public function forget(Association $association): void
    {
        Cache::forget($this->cacheKey($association->getKey()));
    }

    public function defaults(): array
    {
        return [
            self::ABOUT => '',
            self::PRIMARY_COLOR => '#0f766e',
            self::SECONDARY_COLOR => '#f59e0b',
            self::LOGO_PATH => null,
            self::INSTAGRAM_URL => null,
            self::NATIONAL_BODY_URL => null,
        ];
    }

    private function cacheKey(int $associationId): string
    {
        return "association:{$associationId}:settings";
    }
}
