<?php

namespace Lebenlabs\SimpleCMS\Console\Commands;

use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;
use Lebenlabs\SimpleCMS\Models\Categoria;
use Lebenlabs\SimpleCMS\Models\Imagen;
use Lebenlabs\SimpleCMS\Models\Menu;
use Lebenlabs\SimpleCMS\Models\MenuItem;
use Lebenlabs\SimpleCMS\Models\Publicacion;
use Lebenlabs\SimpleStorage\Services\SimpleStorageService;

class CleanDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lebenlabs:simplecms:clean-db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpiar todos los registros de la base de datos pertenecientes a SimpleCMS';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var SimpleStorageService
     */
    private $storage;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(EntityManager $em, SimpleStorageService $storage)
    {
        parent::__construct();

        $this->em = $em;
        $this->storage = $storage;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Doctrine\DBAL\DBALException
     */
    public function handle()
    {

        $this->warn("Este comando procedera a eliminar todos los registros de las tablas lebenlabs_*");

        if (!$this->confirm('Desea continuar?')) {
            return false;
        }

        $conn = $this->em
            ->getConnection();

        $this->info("Se elimina todo lo existente en las tablas de SimpleCMS");

        $this->info("Se eliminan las publicaciones");
        $qb = $this->em->createQueryBuilder();
        $qb->delete(Publicacion::class, 'p');
        $query = $qb->getQuery();
        $query->getResult();

        $this->info("Se eliminan las categorias");
        $qb = $this->em->createQueryBuilder();
        $qb->delete(Categoria::class, 'c');
        $query = $qb->getQuery();
        $query->getResult();

        $this->info("Se eliminan las imagenes");
        $qb = $this->em->createQueryBuilder();
        $qb->delete(Imagen::class, 'i');
        $query = $qb->getQuery();
        $query->getResult();

        $this->info("Se eliminan los menu items");
        $conn->exec('SET FOREIGN_KEY_CHECKS = 0;');
        $qb = $this->em->createQueryBuilder();
        $qb->delete(MenuItem::class, 'c');
        $query = $qb->getQuery();
        $query->getResult();
        $conn->exec('SET FOREIGN_KEY_CHECKS = 1;');

        $this->info("Se eliminan los menu");
        $qb = $this->em->createQueryBuilder();
        $qb->delete(Menu::class, 'c');
        $query = $qb->getQuery();
        $query->getResult();

        $this->info("Se eliminan los storage items");
        //The filter is beacuse I HATE Doctrine and something rare on where condition with the '\'
        $simpleCMSStorageItems = $this->storage->findAll();

        foreach ($simpleCMSStorageItems as $simpleCMSStorageItem) {
            if (strpos($simpleCMSStorageItem->getEntidadId(), 'Lebenlabs\\SimpleCMS\\Models\\') === 0) {
                $this->storage->remove($simpleCMSStorageItem->getId());
            }
        }
    }
}
