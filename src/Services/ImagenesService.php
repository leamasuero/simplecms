<?php

namespace Lebenlabs\SimpleCMS\Services;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Lebenlabs\SimpleCMS\Http\Requests\StoreImagenRequest;
use Lebenlabs\SimpleCMS\Models\Imagen;
use Lebenlabs\SimpleCMS\Models\Publicacion;
use Lebenlabs\SimpleCMS\Repositories\ImagenRepository;

class ImagenesService
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ImagenRepository
     */
    private $imagenRepository;

    /**
     * @var Storage
     */
    private $imagenesStorage;

    /**
     * @var ImageManager
     */
    private $imageManager;

    public function __construct(EntityManagerInterface $em, ImageManager $imageManager)
    {
        $this->em = $em;
        $this->imagenRepository = $this->em->getRepository(Imagen::class);

        $this->imagenesStorage = Storage::disk('simplecms_imagenes');

        $this->imageManager = $imageManager;
    }

    public function findImagen($id)
    {
        return $this->imagenRepository->find($id);
    }

    /**
     * @param StoreImagenRequest $request
     * @param Publicacion $publicacion
     * @return mixed
     */
    public function guardarImagen(StoreImagenRequest $request, Publicacion $publicacion)
    {
        $imagen = new Imagen($request->file('imagen'));

        // Original
        $interventionImage = $this->imageManager->make($request->file('imagen')->getPathname());
        $this->imagenesStorage->put($imagen->getFilename(), $interventionImage->encode());

        // Thumbnail
        $thumbWidth =  config('simplecms.imagenes.thumbnail.width', 500);
        $thumbHeight =  config('simplecms.imagenes.thumbnail.height', 500);
        $interventionImage->fit($thumbWidth, $thumbHeight);
        $this->imagenesStorage->put($imagen->getThumbFilename(), $interventionImage->encode());

        //Set de la imagen en Publicacion
        $publicacion->setImagen($imagen);

        return $imagen;
    }

    /**
     * @param Imagen $imagen
     */
    public function eliminarImagen(Imagen $imagen)
    {
        $this->imagenesStorage->delete($imagen->getFileName());
        $this->imagenesStorage->delete($imagen->getThumbFileName());

        $this->em->remove($imagen);
        $this->em->flush();
    }
}
