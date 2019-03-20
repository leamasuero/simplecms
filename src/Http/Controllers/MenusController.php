<?php

namespace Lebenlabs\SimpleCMS\Http\Controllers;

use Lebenlabs\SimpleCMS\Http\Middleware\CanEditMenu;
use Lebenlabs\SimpleCMS\SimpleCMS;

class MenusController extends Controller
{

    /**
     * @var SimpleCMS
     */
    private $simpleCMSProvider;

    public function __construct(SimpleCMS $simpleCMSProvider)
    {
        $this->simpleCMSProvider = $simpleCMSProvider;
        $this->middleware('web');
        $this->middleware(CanEditMenu::class);
    }

    /**
     * Listado de menus creados
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $menus = $this->simpleCMSProvider->findAllMenus();

        return view('Lebenlabs/SimpleCMS::Menus.index', compact('menus'));
    }

}
