<?php

namespace Lebenlabs\SimpleCMS\Models;

use DateTime;
use Illuminate\Support\Str;

class Categoria
{

    public static $tabla = 'simplecms_categorias';

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var boolean
     */
    private $destacada;

    /**
     * @var boolean
     */
    private $publicada;

    /**
     * @var boolean
     */
    private $protegida;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var array
     */
    private $publicaciones;

    /**
     * @var DateTime
     * */
    private $createdAt;

    /**
     * @var DateTime
     * */
    private $updatedAt;

    public function __construct(string $nombre = null, bool $publicada = false, bool $destacada = false, bool $protegida = false)
    {
        $this->nombre = $nombre;
        $this->setSlug($nombre);
        $this->publicada = true;
        $this->destacada = false;
        $this->protegida = false;
        $this->createdAt = new DateTime;
        $this->updatedAt = new DateTime;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getDestacada()
    {
        return $this->destacada;
    }

    public function getPublicada()
    {
        return $this->publicada;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        $this->setSlug($nombre);
        return $this;
    }

    public function setDestacada($destacada)
    {
        $this->destacada = $destacada;
        return $this;
    }

    public function setPublicada($publicada)
    {
        $this->publicada = $publicada;
        return $this;
    }

    private function setSlug($nombre)
    {
        $this->slug = Str::slug($nombre);
        return $this;
    }

    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    function onPrePersist()
    {
        $this->updatedAt = new DateTime;
    }

    function setProtegida($protegida)
    {
        $this->protegida = $protegida;
        return $this;
    }

    /**
     * Si la categoria esta protegida no puede ser eliminada
     *
     * @return boolean
     */
    public function isProtegida()
    {
        return $this->protegida;
    }

    public function __toString()
    {
        return "{$this->nombre}";
    }

    public function isPublicada()
    {
        return $this->publicada;
    }

    public function isDestacada()
    {
        return $this->destacada;
    }

    public function getUrl()
    {
        return route('publico.publicaciones.categoria.index', $this->getSlug());
    }
}
