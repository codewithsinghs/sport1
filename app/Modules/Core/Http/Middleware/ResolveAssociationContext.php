<?php

namespace App\Modules\Core\Http\Middleware;

use App\Modules\Association\Services\AssociationResolver;
use App\Modules\Core\Association\AssociationContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveAssociationContext
{
    public function __construct(
        private readonly AssociationResolver $resolver,
        private readonly AssociationContext $context,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $this->context->set($this->resolver->resolve($request));

        return $next($request);
    }
}
