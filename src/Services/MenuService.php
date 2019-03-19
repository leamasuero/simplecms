<?php

namespace Lebenlabs\SimpleCMS\Services;

use Doctrine\ORM\EntityManagerInterface;
use Lebenlabs\SimpleCMS\Models\Menu;
use Lebenlabs\SimpleCMS\Repositories\MenuRepository;

class MenuService
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var MenuRepository
     */
    private $menuRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->menuRepository = $this->em->getRepository(Menu::class);
    }

    /**
     * Obtiene todos los Menus que existen
     *
     * @return array
     */
    public function findAllMenus()
    {
        return $this->menuRepository->findAll();
    }

    /**
     * Obtiene el Menu por el nombre dado
     *
     * @param string $nombre
     * @return mixed
     */
    public function findMenuByNombre($nombre)
    {
        return $this->menuRepository->findOneByNombre($nombre);
    }

    /**
     * Obtiene el Menu por el id dado
     *
     * @param int $id
     * @return mixed
     */
    public function findMenuById(int $id)
    {
        return $this->menuRepository->findOneById($id);
    }
}
