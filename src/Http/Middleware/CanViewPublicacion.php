<?php

namespace Lebenlabs\SimpleCMS\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Lebenlabs\SimpleCMS\Services\PublicacionesService;

class CanViewPublicacion
{

    /**
     * @var PublicacionesService
     */
    private $publicacionesService;

    public function __construct(PublicacionesService $publicacionesService)
    {
        // Register services
        $this->publicacionesService = $publicacionesService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $slug           = $request->route('slug');
        $publicacion    = $this->publicacionesService->findPublicacionBySlug($slug);

        if (!$publicacion) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Not Found', 404);
            } else {
                abort(404);
            }
        }

        if (Auth::guard($guard)->guest()) {
            // If user is not authenticated then we just need to check
            // if the publicacion is publicada
            if (!$publicacion->getPublicada()) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response('Not Found', 404);
                } else {
                    abort(404);
                }
            }

        } else {

            // If we have an user authenticated then the control is made in entity
            if (!Auth::user()->canViewPublicacion($publicacion)) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response('Not Found', 404);
                } else {
                    abort(404);
                }
            }
        }


        if (!$publicacion->isPublicada()) {
            flash(trans('Lebenlabs/SimpleCMS::publicaciones.publicacion_no_publicada'))->info();
        }

        return $next($request);
    }
}
