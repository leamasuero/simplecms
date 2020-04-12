<?php

namespace Lebenlabs\SimpleCMS\Models;

use DateTime;
use Illuminate\Support\Str;
use Lebenlabs\SimpleCMS\Interfaces\Shareable;
use Lebenlabs\SimpleStorage\Exceptions\UnStorableItemException;
use Lebenlabs\SimpleStorage\Interfaces\Storable;

class Publicacion implements Shareable, Storable
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $titulo;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $extracto;

    /**
     * @var string
     */
    private $cuerpo;

    /**
     * @var boolean
     */
    private $publicada;

    /**
     * @var boolean
     */
    private $protegida;

    /**
     * @var boolean
     */
    private $destacada;

    /**
     * @var boolean
     */
    private $privada;

    /**
     * @var boolean
     */
    private $notificable;
    /**
     * @var \DateTime
     */
    private $notificadaAt;

    /**
     * @var Categoria
     * */
    private $categoria;

    /**
     * @var DateTime
     * */
    private $fechaPublicacion;

    /**
     * @var DateTime
     * */
    private $createdAt;

    /**
     * @var DateTime
     * */
    private $updatedAt;

    public function __construct($titulo = null, $extracto = null, $cuerpo = null)
    {
        $this->titulo = $titulo;
        $this->setSlug($titulo);
        $this->extracto = $extracto;
        $this->cuerpo = $cuerpo;

        $this->publicada = false;
        $this->destacada = false;
        $this->privada = false;
        $this->protegida = false;
        $this->notificable = false;
        $this->notificadaAt = null;

        $this->createdAt = new \DateTime;
        $this->updatedAt = new \DateTime;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    private function setSlug($titulo)
    {
        $this->slug = Str::slug($titulo);
        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getExtracto()
    {
        return $this->extracto;
    }

    public function getCuerpo()
    {
        return $this->cuerpo;
    }

    public function getPublicada()
    {
        return $this->publicada;
    }

    public function isPublicada()
    {
        return $this->publicada;
    }

    /**
     * @return bool
     */
    public function isNotificable(): bool
    {
        return $this->notificable;
    }

    /**
     * @param bool $notificable
     * @return Publicacion
     */
    public function setNotificable(bool $notificable): Publicacion
    {
        $this->notificable = $notificable;
        return $this;
    }

    public function isNotificada(): bool
    {
        return $this->notificadaAt instanceof DateTime;
    }

    /**
     * @return DateTime
     */
    public function getNotificadaAt(): ?DateTime
    {
        return $this->notificadaAt;
    }

    /**
     * @param DateTime $notificadaAt
     * @return Publicacion
     */
    public function setNotificadaAt(DateTime $notificadaAt): Publicacion
    {
        $this->notificadaAt = $notificadaAt;
        return $this;
    }

    public function getDestacada()
    {
        return $this->destacada;
    }

    public function isDestacada()
    {
        return $this->destacada;
    }

    public function getCategoria()
    {
        return $this->categoria;
    }

    public function getFechaPublicacion(): ?DateTime
    {
        return $this->fechaPublicacion;
    }

    public function getCreatedAt(): DateTime
    {
        return clone $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return clone $this->updatedAt;
    }

    /**
     * Chequea si la publicacion es privada
     *
     * @return bool
     */
    public function isPrivada(): bool
    {
        return $this->privada;
    }

    /**
     * Set de la publicacion es privada
     *
     * @param bool $privada
     */
    public function setPrivada(bool $privada): Publicacion
    {
        $this->privada = $privada;
        return $this;
    }


    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }


    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
        $this->setSlug($titulo);
        return $this;
    }

    public function setExtracto($extracto)
    {
        $this->extracto = $extracto;
        return $this;
    }

    public function setCuerpo($cuerpo)
    {
        $this->cuerpo = $cuerpo;
        return $this;
    }

    public function setPublicada($publicada)
    {
        $this->publicada = $publicada;
        return $this;
    }

    public function setDestacada($destacado)
    {
        $this->destacada = $destacado;
        return $this;
    }

    public function setCategoria(Categoria $categoria = null)
    {
        $this->categoria = $categoria;
        return $this;
    }

    public function setFechaPublicacion(DateTime $fechaPublicacion)
    {
        $this->fechaPublicacion = $fechaPublicacion;
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

    public function __toString()
    {
        return $this->getTitulo();
    }

    /**
     * Si la publicacion esta protegida no puede ser eliminada
     *
     * @return boolean
     */
    public function isProtegida()
    {
        return $this->protegida;
    }

    function setProtegida($protegida)
    {
        $this->protegida = $protegida;
        return $this;
    }

    public function getStorageId()
    {
        if (!$this->getId()) {
            throw new UnStorableItemException();
        }

        return sprintf("%s:%s", get_class($this), $this->getId());
    }

    public function getMetaDescription()
    {
        return sprintf("%s | %s", $this->getTitulo(), $this->getExtracto());
    }

    public function getMetaImage()
    {
        return asset('img/intro-bg-shorted.png');
    }

    public function getIndexRoute()
    {
        return route('simplecms.publicaciones.index');
    }
}
