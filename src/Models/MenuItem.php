<?php

namespace Lebenlabs\SimpleCMS\Models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ORM\Entity(repositoryClass="Lebenlabs\SimpleCMS\Repositories\MenuItemRepository")
 * @ORM\Table(name="lebenlabs_simplecms_menu_items")
 * @ORM\HasLifecycleCallbacks
 */
class MenuItem
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $nombre;

    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $nivel;

    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $orden;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private $visible;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private $tiene_hijos;

    /**
     * @ORM\ManyToOne(targetEntity="MenuItem", inversedBy="hijos")
     * @ORM\JoinColumn(name="menu_item_id", referencedColumnName="id", nullable=true)
     * @var MenuItem
     * */
    private $padre;

    /**
     * Un menu item puede tener muchos hijos
     *
     * @OneToMany(targetEntity="MenuItem", mappedBy="padre", fetch="EAGER", cascade={"remove"})
     */
    private $hijos;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $accion;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private $externo;


    /**
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="menutItems")
     * @ORM\JoinColumn(name="menu_id", referencedColumnName="id")
     * @var Menu
     * */
    private $menu;

    public function __construct(Menu $menu)
    {
        $this->visible = true;
        //Por defecto van a ser todos externos hasta que se desarrolle la otra feature
        $this->externo = true;
        $this->hijos = new ArrayCollection();
        $this->tiene_hijos = false;
        $this->nivel = 0;
        $this->menu = $menu;
    }

    function getId()
    {
        return $this->id;
    }

    function getNombre()
    {
        return $this->nombre;
    }

    function getNivel()
    {
        return $this->nivel;
    }

    function getOrden()
    {
        return $this->orden;
    }

    function getVisible()
    {
        return $this->visible;
    }

    public function isVisible()
    {
        return $this->visible;
    }

    function getTieneHijos()
    {
        return $this->tiene_hijos;
    }

    function getPadre()
    {
        return $this->padre;
    }

    function getAccion()
    {
        return $this->accion;
    }

    public function getAccionArray()
    {
        if ($this->accion == null) {
            return [];
        }

        $accion = json_decode($this->accion);

        if (!$accion) {
            return [];
        }

        return [];
    }

    function getAccionRoute()
    {
        $emptyRoute = 'javascript:void(0)';
        if ($this->accion == null) {
            return $emptyRoute;
        }

        $accion = json_decode($this->accion);

        if (!$accion) {
            return $emptyRoute;
        }

        if ($this->externo) {
            return $this->getEnlaceExterno();

        } else {
            if (isset($accion->name)) {

                if (isset($accion->parameters)) {
                    return route($accion->name, $accion->parameters);
                }

                return route($accion->name);
            } else {
                return $emptyRoute;
            }
        }
    }

    /**
     * Add Http if no Ftp, Http or Https in front
     *
     * @param $url
     * @return string
     */
    private function addHttp($url) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }

        return $url;
    }

    function getHijos()
    {
        if ($this->tiene_hijos > 0) {
            return $this->hijos;
        } else {
            return [];
        }
    }

    /**
     * Obtiene el Menu al que pertenece el MenuItem
     *
     * @return Menu
     */
    function getMenu()
    {
        return $this->menu;
    }

    function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    function setNivel($nivel)
    {
        $this->nivel = $nivel;
        return $this;
    }

    function setOrden($orden)
    {
        $this->orden = $orden;
        return $this;
    }

    function setVisible($visible)
    {
        $this->visible = (boolean) $visible;
        return $this;
    }

    function setTieneHijos($tiene_hijos)
    {
        $this->tiene_hijos = (boolean) $tiene_hijos;
        return $this;
    }

    function setPadre(MenuItem $padre = null)
    {
        $this->padre = $padre;
        return $this;
    }

    function setAccion($accion)
    {
        if (is_array($accion)) {
            $this->accion = json_encode($accion);
        } else {
            $this->accion = null;
        }

        return $this;
    }

    /**
     * Setea la acción tomando un json string
     * que debe contener al menos:
     *  - El name y los parametro en caso de ruta interna
     *  - El href en caso de ruta externa
     *
     * @param $accion
     * @return $this
     */
    function setAccionFromString($accion)
    {
        if ($accion && json_decode($accion)) {
            $this->accion = $accion;
        } else {
            $this->accion = null;
        }

        return $this;
    }



    /**
     * Set del Menu al que pertenece el MenuItem
     *
     * @param Menu $menu
     * @return $this
     */
    function setMenu(Menu $menu)
    {
        $this->menu = $menu;
        return $this;
    }


    /**
     * @param bool $externo
     * @return $this
     */
    public  function  setExterno($externo)
    {
        $this->externo = (boolean) $externo;
        return $this;
    }

    /**
     * @return bool
     */
    public  function  getExterno()
    {
        return $this->externo;
    }

    /**
     * Obtiene el enlace cuando un menu item esta configurado
     * como externo
     *
     * @return string
     */
    public  function  getEnlaceExterno()
    {
        $emptyRoute =  'javascript::void(0)';

        if (!$this->externo) {
            return $emptyRoute;
        }

        if ($this->accion == null) {
            return $emptyRoute;
        }

        $accion = json_decode($this->accion);

        if (!$accion) {
            return  $emptyRoute;
        }

        if ($accion->href) {
            return $accion->href;
        } else {
            return $emptyRoute;
        }
    }

    /**
     * Obtiene el enlace cuando un menu item esta configurado
     * como externo
     *
     * @return string
     */
    public function getEnlaceExternoIfSet()
    {
        $accion = json_decode($this->accion);

        if ($accion && $accion->href) {
            return $this->getEnlaceExterno();
        }

        return '';
    }


    /**
     * Set del enlace cuando un menu item esta configurado
     * como externo
     *
     * @return string
     */
    public  function  setEnlaceExterno($enlace)
    {
        if ($this->externo && $enlace) {
            $this->accion = json_encode([
                'href' =>   $this->addHttp($enlace)
            ]);
        } else {
            $this->accion = null;
        }

        return $this;
    }

    /**
     * Devuelve true si el MenuItem pertenece al Menu pasado como parametro
     *
     * @param Menu $menu
     * @return bool
     */
    public function perteneceAMenu(Menu $menu)
    {
        return ($menu == $this->getMenu());
    }

    public function __toString()
    {
        if (!$this->getPadre()) {
            return "[{$this->getMenu()}] - {$this->getNombre()}";
        } else {
            return "[{$this->getMenu()}] - {$this->getPadre()->getNombre()} -> {$this->getNombre()}";
        }

    }
}
