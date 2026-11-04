<?php

namespace App\Modules\Core\Http\Middleware;

use App\Modules\Core\Association\AssociationContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAssociationContext
{
    public function __construct(
        private readonly AssociationContext $context,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $association = $this->context->association();

        abort_if($association === null, 404);

        $user = $request->user();

        // The context may originate from a trusted public domain, but an
        // authenticated user must never be served another association's data.
        if ($user !== null && $user->association_id !== null && $user->association_id !== $association->getKey()) {
            abort(403);
        }

        return $next($request);
    }
}
