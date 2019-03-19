<?php

namespace Lebenlabs\SimpleCMS\Database\Seeds;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Database\Seeder;
use Lebenlabs\SimpleCMS\Models\Menu;
use Lebenlabs\SimpleCMS\Models\MenuItem;
use Lebenlabs\SimpleCMS\Repositories\MenuRepository;
use Exception;

class MenuItemTableSeeder extends Seeder
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var MenuRepository
     */
    private $menuRepository;

    public function __construct()
    {
        $this->em = app('em');
        $this->menuRepository = $this->em->getRepository(Menu::class);
    }

    public function run()
    {

        $menuFrontend = $this->menuRepository->findOneByNombre('frontend');

        if (!$menuFrontend) {
            throw new Exception("Frontend menu tiene que existir. Corra el MenuTableSeeder primero.");
        }

        $site = env('APP_URL');

        $menuItems = [
            [
                'nombre' => 'Inicio',
                'nivel' => 0,
                'orden' => 1,
                'visible' => true,
                'tiene_hijos' => false,
                'padre' => null,
                'externo' => true,
                'accion' => [
                    'href' => $site
                ],
                'hijos' => []
            ],
            [
                'nombre' => 'La institución',
                'nivel' => 0,
                'orden' => 2,
                'visible' => true,
                'tiene_hijos' => true,
                'padre' => null,
                'accion' => null,
                'externo' => false,
                'hijos' => [
                    [
                        'nombre' => 'Autoridades',
                        'nivel' => 1,
                        'orden' => 1,
                        'visible' => true,
                        'tiene_hijos' => false,
                        'padre' => true,
                        'externo' => true,
                        'accion' => [
                            'href' => $site . '/publicaciones/autoridades',
                        ],
                        'hijos' => []
                    ],
                    [
                        'nombre' => 'Formularios',
                        'nivel' => 1,
                        'orden' => 2,
                        'visible' => true,
                        'tiene_hijos' => false,
                        'padre' => true,
                        'externo' => true,
                        'accion' => [
                            'href' => $site . '/publicaciones/formularios',
                        ],
                        'hijos' => []
                    ],
                    [
                        'nombre' => 'Colegiados',
                        'nivel' => 1,
                        'orden' => 3,
                        'visible' => true,
                        'tiene_hijos' => false,
                        'externo' => true,
                        'padre' => true,
                        'accion' => [
                            'href' => $site . '/publicaciones/colegiados',
                        ],
                        'hijos' => []
                    ],
                    [
                        'nombre' => 'Legales',
                        'nivel' => 1,
                        'orden' => 4,
                        'visible' => true,
                        'tiene_hijos' => false,
                        'externo' => true,
                        'padre' => true,
                        'accion' => [
                            'href' => $site . '/publicaciones/legales',
                        ],
                        'hijos' => []
                    ],
                    [
                        'nombre' => 'Recomendaciones',
                        'nivel' => 1,
                        'orden' => 5,
                        'visible' => true,
                        'tiene_hijos' => false,
                        'externo' => true,
                        'padre' => true,
                        'accion' => [
                            'href' => $site . '/publicaciones/recomendaciones',
                        ],
                        'hijos' => []
                    ],
                    [
                        'nombre' => 'Reseña histórica',
                        'nivel' => 1,
                        'orden' => 6,
                        'visible' => true,
                        'tiene_hijos' => false,
                        'externo' => true,
                        'padre' => true,
                        'accion' => [
                            'href' => $site . '/publicaciones/resena-historica',
                        ],
                        'hijos' => []
                    ],
                ]
            ],
            [
                'nombre' => 'Novedades',
                'nivel' => 0,
                'orden' => 3,
                'visible' => true,
                'tiene_hijos' => false,
                'externo' => true,
                'padre' => null,
                'accion' => [
                    'href' => $site . '/publicaciones/novedades',
                ],
                'hijos' => []
            ],
            [
                'nombre' => 'Formación Profesional',
                'nivel' => 0,
                'orden' => 4,
                'visible' => true,
                'tiene_hijos' => true,
                'padre' => null,
                'accion' => null,
                'externo' => false,
                'hijos' => [
                    [
                        'nombre' => 'Cursos ProFoco',
                        'nivel' => 1,
                        'orden' => 1,
                        'visible' => true,
                        'tiene_hijos' => false,
                        'externo' => true,
                        'padre' => null,
                        'accion' => [
                            'href' => $site . '/publicaciones/cursos-profoco',
                        ],
                        'hijos' => []
                    ],
                    [
                        'nombre' => 'Cursos de otra entidad',
                        'nivel' => 1,
                        'orden' => 2,
                        'visible' => true,
                        'tiene_hijos' => false,
                        'externo' => true,
                        'padre' => null,
                        'accion' => [
                            'href' => $site . '/publicaciones/cursos-de-otra-entidad',
                        ],
                        'hijos' => []
                    ],
                    [
                        'nombre' => 'Congresos',
                        'nivel' => 1,
                        'orden' => 3,
                        'visible' => true,
                        'tiene_hijos' => false,
                        'externo' => true,
                        'padre' => null,
                        'accion' => [
                            'href' => $site . '/publicaciones/congresos',
                        ],
                        'hijos' => []
                    ],
                    [
                        'nombre' => 'Certificación',
                        'nivel' => 1,
                        'orden' => 4,
                        'visible' => true,
                        'tiene_hijos' => false,
                        'externo' => true,
                        'padre' => null,
                        'accion' => [
                            'href' => $site . '/publicaciones/certificacion',
                        ],
                        'hijos' => []
                    ],
                    [
                        'nombre' => 'Requisitos para la especialidad',
                        'nivel' => 1,
                        'orden' => 5,
                        'visible' => true,
                        'tiene_hijos' => false,
                        'externo' => true,
                        'padre' => null,
                        'accion' => [
                            'href' => $site . '/publicaciones/requisitos-para-la-especialidad',
                        ],
                        'hijos' => []
                    ],
                    [
                        'nombre' => 'Residencias',
                        'nivel' => 1,
                        'orden' => 6,
                        'visible' => true,
                        'tiene_hijos' => false,
                        'externo' => true,
                        'padre' => null,
                        'accion' => [
                            'href' => $site . '/publicaciones/residencias',
                        ],
                        'hijos' => []
                    ]
                ]
            ],
            [
                'nombre' => 'Laboratorio',
                'nivel' => 0,
                'orden' => 5,
                'visible' => true,
                'tiene_hijos' => true,
                'externo' => false,
                'padre' => null,
                'accion' => null,
                'hijos' => [
                    [
                        'nombre' => 'Laboratorios Habilitados',
                        'nivel' => 1,
                        'orden' => 1,
                        'visible' => true,
                        'tiene_hijos' => false,
                        'externo' => true,
                        'padre' => null,
                        'accion' => [
                            'href' => $site . '/publicaciones/laboratorios-habilitados',
                        ],
                        'hijos' => []
                    ],
                    [
                        'nombre' => 'Habilitación',
                        'nivel' => 1,
                        'orden' => 2,
                        'visible' => true,
                        'tiene_hijos' => false,
                        'externo' => true,
                        'padre' => null,
                        'accion' => [
                            'href' => $site . '/publicaciones/habilitacion',
                        ],
                        'hijos' => []
                    ],
                    [
                        'nombre' => 'Inspectores',
                        'nivel' => 1,
                        'orden' => 3,
                        'visible' => true,
                        'tiene_hijos' => false,
                        'externo' => true,
                        'padre' => null,
                        'accion' => [
                            'href' => $site . '/publicaciones/inspectores',
                        ],
                        'hijos' => []
                    ],
                ]
            ],
        ];

        foreach ($menuItems as $menuItem) {
            $menuItemPadre = new MenuItem($menuFrontend);

            $menuItemPadre->setNombre($menuItem['nombre'])
                ->setNivel($menuItem['nivel'])
                ->setOrden($menuItem['orden'])
                ->setVisible($menuItem['visible'])
                ->setTieneHijos($menuItem['tiene_hijos'])
                ->setPadre(null)
                ->setExterno($menuItem['externo'])
                ->setAccion($menuItem['accion']);

            $this->em->persist($menuItemPadre);

            if (count($menuItem['hijos'])) {
                foreach ($menuItem['hijos'] as $menuItemHijo) {
                    $newMenuItemHijo = new MenuItem($menuFrontend);

                    $newMenuItemHijo->setNombre($menuItemHijo['nombre'])
                        ->setNivel($menuItemHijo['nivel'])
                        ->setOrden($menuItemHijo['orden'])
                        ->setVisible($menuItemHijo['visible'])
                        ->setTieneHijos($menuItemHijo['tiene_hijos'])
                        ->setPadre($menuItemPadre)
                        ->setExterno($menuItemHijo['externo'])
                        ->setAccion($menuItemHijo['accion']);

                    $this->em->persist($newMenuItemHijo);
                }
            }
        }

        $this->em->flush();
    }
}
