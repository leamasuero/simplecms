<?php

namespace Lebenlabs\SimpleCMS\Console\Commands;

use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;
use Lebenlabs\SimpleCMS\Models\Menu;
use Lebenlabs\SimpleCMS\SimpleCMS;

class CreateMenuCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lebenlabs:simplecms:create-menu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando encargado de la creación de un menu';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var SimpleCMS
     */
    private $simpleCMS;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(EntityManager $em, SimpleCMS $simpleCMS)
    {
        parent::__construct();

        $this->em = $em;
        $this->simpleCMS = $simpleCMS;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Doctrine\DBAL\DBALException
     */
    public function handle()
    {
        $menu = new Menu();

        $this->info("Tener en cuenta que el nombre que va a ingresar para el frontend sera el utilizado para referenciarlo desde el código");

        $menu->setNombre($this->ask('Nombre?'));

        if ($this->simpleCMS->findMenuByNombre($menu->getNombre())) {
            $this->error("Ya existe un menu con el nombre {$menu->getNombre()}");
        } else {
            $this->em->persist($menu);
            $this->em->flush();

            $this->info('Menú creado exitosamente');
        }
    }
}
