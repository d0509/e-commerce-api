<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConvertValidationErrorsToJson
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

    if ($response instanceof JsonResponse && $response->status() === 422 && $request->expectsJson()) {
        $originalContent = $response->getOriginalContent();

        $response->setData([
            'error' => [
                'message' => 'The given data was invalid.',
                'errors' => $originalContent['errors'],
            ],
        ]);

        $response->setStatusCode(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    return $response;
    }
}
