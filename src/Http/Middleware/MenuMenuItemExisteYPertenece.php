<?php

namespace Lebenlabs\SimpleCMS\Http\Middleware;

use Closure;
use Lebenlabs\SimpleCMS\Services\MenuItemsService;
use Lebenlabs\SimpleCMS\Services\MenuService;

class MenuMenuItemExisteYPertenece
{

    /**
     * @var MenuItemsService
     */
    private $menuItemsService;

    /**
     * @var MenuService
     */
    private $menuService;

    public function __construct(MenuItemsService $menuItemsService, MenuService $menuService)
    {
        // Register services
        $this->menuItemsService = $menuItemsService;
        $this->menuService = $menuService;
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
        //Obtenemos los parametros de Menu y MenuItem
        $menuId = (int) $request->route('menu');
        $menuItemId = (int) $request->route('menu_item');

        //Buscamos en la base ambos objetos
        $menu = $this->menuService->findMenuById($menuId);
        $menuItem = $this->menuItemsService->findMenuItemById($menuItemId);


        // Menu y MenuItem deben existir
        if (!$menu || !$menuItem) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Resource does not exist.', 404);
            } else {
                abort(404);
            }
        }

        // El MenuItem debe pertenecer al menu
        if (!$menuItem->perteneceAMenu($menu)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                abort(403);
            }
        }


        return $next($request);
    }
}
