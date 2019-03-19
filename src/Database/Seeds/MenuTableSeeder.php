<?php

namespace Lebenlabs\SimpleCMS\Database\Seeds;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Database\Seeder;
use Lebenlabs\SimpleCMS\Models\Menu;

class MenuTableSeeder extends Seeder
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct()
    {
        $this->em = app('em');
    }

    public function run()
    {
        $menu = new Menu();
        $menu->setNombre('frontend');
        $this->em->persist($menu);
        $this->em->flush();

    }
}
