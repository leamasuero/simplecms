<?php

namespace Lebenlabs\SimpleCMS\Models;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Lebenlabs\SimpleCMS\Repositories\CategoriaRepository")
 * @ORM\Table(name="lebenlabs_simplecms_categorias")
 * @ORM\HasLifecycleCallbacks
 */
class Categoria
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     * @var string
     */
    private $nombre;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private $destacada;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private $publicada;

    /**
     * @ORM\Column(type="boolean", options={"default":0}))
     * @var boolean
     */
    private $protegida;

    /**
     * @Gedmo\Slug(fields={"nombre", "id"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

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

    public function __construct($nombre = null)
    {
        $this->nombre = $nombre;
        $this->publicada = true;
        $this->destacada = false;
        $this->protegida = false;
        $this->created_at = new DateTime;
        $this->updated_at = new DateTime;
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
        return $this->created_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
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

    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    public function setCreatedAt(DateTime $createdAt)
    {
        $this->created_at = $createdAt;
        return $this;
    }

    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updated_at = $updatedAt;
        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    function onPrePersist()
    {
        $this->updated_at = new DateTime;
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
