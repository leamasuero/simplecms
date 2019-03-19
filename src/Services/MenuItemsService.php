<?php

namespace Lebenlabs\SimpleCMS\Services;

use Doctrine\ORM\EntityManagerInterface;
use Lebenlabs\SimpleCMS\Models\MenuItem;
use Lebenlabs\SimpleCMS\Repositories\MenuItemRepository;

class MenuItemsService
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var MenuItemRepository
     */
    private $menuItemRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->menuItemRepository = $this->em->getRepository(MenuItem::class);
    }

    /**
     * Retorna todos los menu items de nivel 0 ordenados
     * por el orden
     *
     * @return array
     */
    public function findAllRootMenuItems()
    {
        return $this->menuItemRepository->findBy(
                [
                    'nivel' => 0,
                    'visible' => 1,
                ],
                [
                    'orden' => 'ASC'
                ]);
    }


    /**
     * Obtiene el Menu Item por el id dado
     *
     * @param int $id
     * @return mixed
     */
    public function findMenuItemById(int $id)
    {
        return $this->menuItemRepository->findOneById($id);
    }

    /**
     * Guarda en la base de datos el MenuItem pasado como parametro
     *
     * @param MenuItem $menuItem
     * @return MenuItem
     */
    public function guardarMenuItem(MenuItem $menuItem)
    {
        $this->em->persist($menuItem);
        $this->em->flush();

        return $menuItem;
    }

    /**
     * Elimina el Menu Item pasado como parametro junto con todos sus
     * menu item hijos en caso de existir
     *
     * @param MenuItem $menuItem
     * @return void
     */
    public function eliminarMenuItem(MenuItem $menuItem)
    {
        $this->em->remove($menuItem);
        $this->em->flush();
    }


}
