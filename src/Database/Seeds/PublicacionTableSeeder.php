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

        //This is just to test all elements
        $cuerpo = '<p><br></p><p><b>Negrita</b></p><p><u>Subrayada</u></p><p><span style=\"background-color: rgb(255, 255, 0);\">Resaltada</span></p><p><br></p><ul><li>Lista elem 1</li><li>Lista elem 2</li></ul><p><br></p><ol><li>Lista ordenada 1</li><li>Lista ordenada 2</li></ol><p><br></p><p><br></p><table class=\"table table-bordered\"><tbody><tr><td>Encabezado</td><td>Allado</td><td>ALl ado</td></tr><tr><td>a asd asd&nbsp;</td><td>asd asd asd&nbsp;</td><td>ss dasd asda d</td></tr><tr><td>asd asd as</td><td>&nbsp; asd asd&nbsp;</td><td>asd a&nbsp;</td></tr></tbody></table><p><br></p><p>Texto normal</p><h1>Titulo 1</h1><h2>Titulo 2</h2><h3>Titulo 3</h3><h4>Titulo 4</h4><blockquote>Esto es un blockquote</blockquote><pre>This will be code</pre><h2><br></h2><h2><br></h2><h2>What is Lorem Ipsum?</h2><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p><p><br></p><h2>Why do we use it?</h2><p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p><h2><br></h2><h2>Where does it come from?</h2><p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p><p><span style=\"font-size: 0.9rem;\">The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</span><br></p><p><br></p><h2>Where can I get some?</h2><p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>';

        $publicacion = new Publicacion($this->faker->words(2 , true), $this->faker->sentence(10), $cuerpo);
        $publicacion->setPublicada(true)
            ->setDestacada(true)
            ->setCategoria($categoria)
            ->setFechaPublicacion(new DateTime());

        $this->simpleCMSProvider->guardarPublicacion($publicacion);
    }
}
