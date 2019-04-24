<?php

namespace Lebenlabs\SimpleCMS\Http\Middleware;

use Closure;
use Lebenlabs\SimpleCMS\Services\PublicacionesService;

class PublicacionExiste
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
        //Obtenemos los parametros de Publicacion
        $publicacionId = (int) $request->route('publicacione');

        //Buscamos en la base la publicacion
        $publicacion = $this->publicacionesService->findPublicacion($publicacionId);

        // La publicaciond ebe existir
        if (!$publicacion) {
            if ($request->ajax() || $request->wantsJson()) {
                return response(trans('Lebenlabs/SimpleCMS::publicaciones.not_found'), 404);
            } else {
                abort(404, trans('Lebenlabs/SimpleCMS::publicaciones.not_found'));
            }
        }

        return $next($request);
    }
}
