<?php

namespace Lebenlabs\SimpleCMS\Http\Controllers;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Exception;
use Lebenlabs\SimpleCMS\Http\Middleware\CanEditMenu;
use Lebenlabs\SimpleCMS\Http\Middleware\MenuMenuItemExisteYPertenece;
use Lebenlabs\SimpleCMS\Http\Requests\StoreMenuItemRequest;
use Lebenlabs\SimpleCMS\Http\Requests\UpdateMenuItemRequest;
use Lebenlabs\SimpleCMS\Models\Menu;
use Lebenlabs\SimpleCMS\Models\MenuItem;
use Lebenlabs\SimpleCMS\SimpleCMS;

class MenuItemsController extends Controller
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var SimpleCMS
     */
    private $simpleCMSProvider;

    public function __construct(EntityManager $em, SimpleCMS $simpleCMSProvider)
    {
        $this->em = $em;

        // Get connection to use transaction
        $this->connection = $this->em->getConnection();

        // Register services
        $this->simpleCMSProvider= $simpleCMSProvider;

        // Register middleware
        $this->middleware('web');
        $this->middleware(CanEditMenu::class);

        //Chequea que los parametros de menu y menu_item tengan sentido y patea en caso contrario
        $this->middleware(MenuMenuItemExisteYPertenece::class, ['only' => ['edit', 'update', 'destroy']]);
    }

    /**
     * Lista los Menu Items para un Menu dado
     *
     * @param int $menuId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function index(int $menuId)
    {
        /* @var $menu Menu */
        $menu = $this->simpleCMSProvider->findMenuById($menuId);

        if (!$menu) {
            return abort(404);
        }

        // Obtengo los menu items padres
        $menuItems = $menu->getRootMenuItems();

        return view('Lebenlabs/SimpleCMS::MenuItems.index', compact('menu', 'menuItems'));
    }

    /**
     * Carga el formulario para la creación de un Menú Item
     *
     * @param int $menuId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function create(int $menuId)
    {
        $create = true;
        /* @var $menu Menu */
        $menu = $this->simpleCMSProvider->findMenuById($menuId);
        /* @var $menuItem MenuItem */
        $menuItem = new MenuItem($menu);

        // Obtengo los menu items padres
        // TODO Ver como hacer un KeyBy con la PUTA clase ArrayCollection de Doctrine
        $menuItems = collect($menu->getRootMenuItems()->toArray())->keyBy(function (MenuItem $menuItem) {
            return $menuItem->getId();
        });

        return view('Lebenlabs/SimpleCMS::MenuItems.create', compact('menu', 'menuItem', 'menuItems', 'create'));
    }


    /**
     * Crea un nuevo Menu Item
     *
     * @param int $menuId
     * @param StoreMenuItemRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    public function store(int $menuId, StoreMenuItemRequest $request)
    {
        /* @var $menu Menu */
        $menu = $this->simpleCMSProvider->findMenuById($menuId);
        /* @var $menuItem MenuItem */
        $menuItem = new MenuItem($menu);

        $menuItem->setNombre($request->get('nombre', null))
            ->setOrden($request->get('orden', null))
            ->setVisible($request->get('visible', null))
            ->setExterno($request->get('externo', null))
            ->setEnlaceExterno($request->get('enlace', null));

        $padreMenuItemId = $request->get('padre', null);

        if ($padreMenuItemId !== null) {
            /* @var $padreMenuItem MenuItem */
            $padreMenuItem = $this->simpleCMSProvider->findMenuItemById((int) $padreMenuItemId);

            if (!$padreMenuItem) {
                throw new Exception(trans('Lebenlabs/SimpleCMS::menu_items.store_failed_padre_inexistente'));
            }

            $menuItem->setPadre($padreMenuItem)
                ->setTieneHijos(false)
                ->setNivel(1);

            $padreMenuItem->setTieneHijos(true);

        }

        try {

            $this->connection->beginTransaction();
            $this->simpleCMSProvider->guardarMenuItem($menuItem);

            if (isset($padreMenuItem)) {
                $this->simpleCMSProvider->guardarMenuItem($padreMenuItem);
            }

            $this->connection->commit();

            flash(trans('Lebenlabs/SimpleCMS::menu_items.store_success'))->success();
            return redirect()->route('simplecms.menus.menu_items.index', $menuId);

        } catch (Exception $ex) {

            $this->connection->rollback();

            flash($ex->getMessage())->error();

            return redirect()->back()
                ->withInput();
        }
    }

    /**
     * Carga el formulario para la edición de los datos de un Menú Item
     * para un menú en particular
     *
     * @param int $menuId
     * @param int $menuItemId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function edit(int $menuId, int $menuItemId)
    {
        $create = false;
        /* @var $menu Menu */
        $menu = $this->simpleCMSProvider->findMenuById($menuId);
        /* @var $menuItem MenuItem */
        $menuItem = $this->simpleCMSProvider->findMenuItemById($menuItemId);

        return view('Lebenlabs/SimpleCMS::MenuItems.edit', compact('menu', 'menuItem', 'create'));
    }


    /**
     * Actualiza los datos del Menu Item
     *
     * @param int $menuId
     * @param int $menuItemId
     * @param UpdateMenuItemRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function update(int $menuId, int $menuItemId, UpdateMenuItemRequest $request)
    {
        /* @var $menuItem MenuItem */
        $menuItem = $this->simpleCMSProvider->findMenuItemById($menuItemId);

        $menuItem->setNombre($request->get('nombre', null))
            ->setOrden($request->get('orden', null))
            ->setVisible($request->get('visible', null))
            ->setExterno($request->get('externo', null))
            ->setEnlaceExterno($request->get('enlace', null));

        try {

            $this->connection->beginTransaction();
            $this->simpleCMSProvider->guardarMenuItem($menuItem);
            $this->connection->commit();

            flash(trans('Lebenlabs/SimpleCMS::menu_items.update_success'))->success();
            return redirect()->route('simplecms.menus.menu_items.index', $menuId);

        } catch (Exception $ex) {
            $this->connection->rollback();

            flash($ex->getMessage())->error();

            return redirect()->back()
                ->withInput();
        }
    }

    /**
     * Elimina el menu item junto con todos los Menu Items ids hijos en caso de que existan
     *
     * @param int $menuId
     * @param int $menuItemId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(int $menuId, int $menuItemId)
    {
        /* @var $menuItem MenuItem */
        $menuItem = $this->simpleCMSProvider->findMenuItemById($menuItemId);

        try {
            $this->connection->beginTransaction();
            $this->simpleCMSProvider->eliminarMenuItem($menuItem);

            $this->em->commit();
            flash(trans('Lebenlabs/SimpleCMS::menu_items.destroy_success'))->success();

        } catch (Exception $ex) {
            $this->connection->beginTransaction();
            flash($ex->getMessage())->error();

        }

        return redirect()->route('simplecms.menus.menu_items.index', $menuId);
    }

}
