<?php

namespace Lebenlabs\SimpleCMS\Database\Seeds;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Lebenlabs\SimpleCMS\Models\Categoria;
use Lebenlabs\SimpleCMS\SimpleCMS;

class CategoriaTableSeeder extends Seeder
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
        $this->simpleCMSProvider = $simpleCMSProvider;
        $this->faker = Factory::create();
    }

    public function run()
    {
        foreach (range(1, 3) as $i) {
            $categoria = new Categoria($this->faker->words(1, true));
            $categoria->setProtegida(false)
                ->setDestacada(true);

            $this->simpleCMSProvider->guardarCategoria($categoria);
        }
    }
}
