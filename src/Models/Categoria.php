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
     * @var DateTime
     * */
    private $createdAt;

    /**
     * @var DateTime
     * */
    private $updatedAt;

    public function __construct(string $nombre = null, bool $publicada = true, bool $destacada = false, bool $protegida = false)
    {
        $this->nombre = $nombre;
        $this->setSlug($nombre);
        $this->publicada = $publicada;
        $this->destacada = $destacada;
        $this->protegida = $protegida;

        $this->createdAt = new DateTime;
        $this->updatedAt = new DateTime;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function getDestacada(): bool
    {
        return (bool)$this->destacada;
    }

    public function getPublicada(): bool
    {
        return (bool)$this->publicada;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function setId(int $id): Categoria
    {
        $this->id = $id;
        return $this;
    }

    public function setNombre($nombre): Categoria
    {
        $this->nombre = $nombre;
        $this->setSlug($nombre);
        return $this;
    }

    public function setDestacada(bool $destacada): Categoria
    {
        $this->destacada = $destacada;
        return $this;
    }

    public function setPublicada(bool $publicada): Categoria
    {
        $this->publicada = $publicada;
        return $this;
    }

    private function setSlug(string $nombre): Categoria
    {
        $this->slug = Str::slug($nombre);
        return $this;
    }

    public function setCreatedAt(DateTime $createdAt): Categoria
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function setUpdatedAt(DateTime $updatedAt): Categoria
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }


    function setProtegida(bool $protegida): Categoria
    {
        $this->protegida = $protegida;
        return $this;
    }

    /**
     * Si la categoria esta protegida no puede ser eliminada
     *
     * @return boolean
     */
    public function isProtegida(): bool
    {
        return (bool)$this->protegida;
    }

    public function __toString()
    {
        return "{$this->nombre}";
    }

    public function isPublicada(): bool
    {
        return (bool)$this->publicada;
    }

    public function isDestacada(): bool
    {
        return (bool)$this->destacada;
    }

    public function getUrl()
    {
        return route('publico.publicaciones.categoria.index', $this->getSlug());
    }
}
