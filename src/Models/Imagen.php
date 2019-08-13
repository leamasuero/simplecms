<?php

namespace Lebenlabs\SimpleCMS\Models;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity
 * @ORM\Table(name="lebenlabs_simplecms_imagenes")
 * @ORM\HasLifecycleCallbacks
 */
class Imagen
{

    //@todo: esto no tiene que estar aca... tenemos que usar el disk
    const IMAGENES_PATH = 'storage/lebenlabs_simplecms/imagenes/publicaciones';
    const THUMBNAIL_PREFIX = 'thumb-';
    const THUMBNAIL_HEIGHT = 500;
    const QUALITY = 90;
    const ENCODING = 'jpg';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=250, unique=true, nullable=false)
     */
    private $filename;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     * */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     * */
    private $updated_at;

    public function __construct(UploadedFile $imagenFile)
    {
        $this->created_at = new DateTime;
        $this->filename = $this->created_at->format('U') . '-' . $imagenFile->getClientOriginalName();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFilePath()
    {
        return public_path() . DIRECTORY_SEPARATOR . self::IMAGENES_PATH . DIRECTORY_SEPARATOR . $this->filename;
    }

    public function getThumbnailFilePath()
    {
        return public_path() . DIRECTORY_SEPARATOR . self::IMAGENES_PATH . DIRECTORY_SEPARATOR . $this->getThumbFilename();
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function getThumbFilename()
    {
        return self::THUMBNAIL_PREFIX . $this->filename;
    }

    public function getUrl()
    {
        return asset(self::IMAGENES_PATH . '/' . $this->filename);
    }

    public function getThumbnailUrl()
    {
        return asset(self::IMAGENES_PATH . '/' . $this->getThumbFilename());
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    public function setCreatedAt(DateTime $createdAt)
    {
        $this->created_at = $createdAt;
    }

    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updated_at = $updatedAt;
    }

    /**
     * @ORM\PrePersist
     */
    function onPrePersist()
    {
        $this->updated_at = new DateTime;
    }
}
