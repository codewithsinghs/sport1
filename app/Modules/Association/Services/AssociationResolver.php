<?php

namespace App\Modules\Association\Services;

use App\Modules\Association\Models\Association;
use App\Modules\Association\Models\AssociationDomain;
use Illuminate\Http\Request;

class AssociationResolver
{
    public function resolve(Request $request): ?Association
    {
        $user = $request->user();

        if ($user?->association_id !== null) {
            // Never fall back to another association for an authenticated user.
            return Association::query()
                ->whereKey($user->association_id)
                ->where('is_active', true)
                ->first();
        }

        if ($user !== null) {
            // Authenticated users without an association use trusted site config.
            return $this->resolveFromRequestHost($request) ?? $this->resolveDefault();
        }

        return $this->resolveFromRequestHost($request) ?? $this->resolveDefault();
    }

    private function resolveFromRequestHost(Request $request): ?Association
    {
        $host = strtolower(rtrim($request->getHost(), '.'));

        $hosts = array_unique([
            $host,
            str_starts_with($host, 'www.') ? substr($host, 4) : 'www.'.$host,
        ]);

        $domain = AssociationDomain::query()
            ->with('association')
            ->whereIn('domain', $hosts)
            ->whereHas('association', fn ($query) => $query->where('is_active', true))
            ->first();

        return $domain?->association;
    }

    private function resolveDefault(): ?Association
    {
        if (! config('association.allow_config_fallback')) {
            return null;
        }

        $slug = config('association.default_slug');

        if (! is_string($slug) || $slug === '') {
            return null;
        }

        return Association::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }
}
