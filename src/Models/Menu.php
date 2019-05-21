<?php

namespace Lebenlabs\SimpleCMS\Models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ORM\Entity(repositoryClass="Lebenlabs\SimpleCMS\Repositories\MenuRepository")
 * @ORM\Table(name="lebenlabs_simplecms_menus")
 * @ORM\HasLifecycleCallbacks
 */
class Menu
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
     * Un menu puede tener muchos menu items
     *
     * @OneToMany(targetEntity="MenuItem", mappedBy="menu", fetch="EAGER")
     * @ORM\OrderBy({"orden" = "ASC"})
     */
    private $menuItems;

    public function __construct($nombre = '')
    {
        $this->nombre = $nombre;
        $this->menuItems = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @return ArrayCollection
     */
    public function getMenuItems()
    {
        return $this->menuItems;
    }

    /**
     * @return ArrayCollection
     */
    public function getRootMenuItems()
    {
        return $this->getMenuItems()->filter( function (MenuItem $menuItem) {
            if ($menuItem->getNivel() == 0) {
                return true;
            }
        });
    }

    /**
     * @param MenuItem $menuItem
     * @return $this
     */
    public function addMenuItem(MenuItem $menuItem)
    {
        $this->menuItems->add($menuItem);
        return $this;
    }

    /**
     * @param string $nombre
     * @return $this
     */
    public function setNombre(string $nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "{$this->getNombre()}";
    }

}
