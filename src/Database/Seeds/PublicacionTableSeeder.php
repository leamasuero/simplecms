<?php

namespace Lebenlabs\SimpleCMS\Database\Seeds;

use DateTime;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Lebenlabs\SimpleCMS\Models\Publicacion;
use Lebenlabs\SimpleCMS\SimpleCMS;

class PublicacionTableSeeder extends Seeder
{

    /**
     * @var Generator
     */
    private $faker;

    /**
     * @var SimpleCMS
     */
    private $simpleCMSProvider;

    public function __construct(SimpleCMS $simpleCMSProvider)
    {
        $this->faker = Factory::create();
        $this->simpleCMSProvider = $simpleCMSProvider;
    }

    public function run()
    {
        $categorias = $this->simpleCMSProvider->findAllCategoriasPublicadasIndexed();

        foreach ($categorias as $categoria) {
            $cuerpo = "";
            foreach ($this->faker->paragraphs(5) as $p) {
                $cuerpo .= "<p>{$p}<p>";
            }

            $publicacion = new Publicacion($this->faker->words(2 , true), $this->faker->sentence(10), $cuerpo);
            $publicacion->setPublicada(true)
                ->setDestacada(true)
                ->setCategoria($categoria)
                ->setFechaPublicacion(new DateTime());

            $this->simpleCMSProvider->guardarPublicacion($publicacion);
        }
    }
}
