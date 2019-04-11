<?php

namespace Lebenlabs\SimpleCMS\Models;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Lebenlabs\SimpleCMS\Interfaces\Shareable;
use Lebenlabs\SimpleStorage\Exceptions\UnStorableItemException;
use Lebenlabs\SimpleStorage\Interfaces\Storable;

/**
 * @ORM\Entity(repositoryClass="Lebenlabs\SimpleCMS\Repositories\PublicacionRepository")
 * @ORM\Table(name="lebenlabs_simplecms_publicaciones")
 * @ORM\HasLifecycleCallbacks
 */
class Publicacion implements Shareable, Storable
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $titulo;

    /**
     * @Gedmo\Slug(fields={"titulo"})
     * @ORM\Column(length=256)
     * @var string
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     * @var string
     */
    private $extracto;

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    private $cuerpo;

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
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private $destacada;

    /**
     * @ORM\ManyToOne(targetEntity="Categoria")
     * @ORM\JoinColumn(name="categoria_id", referencedColumnName="id", nullable=false)
     * @var Categoria
     * */
    private $categoria;

    /**
     * @ORM\ManyToOne(targetEntity="Imagen", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="imagen_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * @var Imagen
     * */
    private $imagen;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     * */
    private $fecha_publicacion;

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

    public function __construct($titulo = null, $extracto = null, $cuerpo = null)
    {
        $this->titulo = $titulo;
        $this->extracto = $extracto;
        $this->cuerpo = $cuerpo;
        $this->publicada = false;
        $this->destacada = false;
        $this->protegida = false;
        $this->created_at = new \DateTime;
        $this->updated_at = new \DateTime;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitulo()
    {
        return $this->titulo;
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

    public function getCategoriaId()
    {
        return $this->categoria ? $this->categoria->getId() : null;
    }

    public function getFechaPublicacion()
    {
        return $this->fecha_publicacion;
    }

    public function getFechaPublicacionFormat($format = 'just-date')
    {
        if ($this->fecha_publicacion) {
            return $this->fecha_publicacion->format(config('simplecms.formats.' . $format));
        }

        return null;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getCreatedAtFormat($format = 'just-date')
    {
        if ($this->created_at) {
            return $this->created_at->format(config('simplecms.formats.' . $format));
        }

        return null;
    }

    public function getUrl()
    {
        return route('publico.publicaciones.show', $this->getSlug());
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

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
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

    public function getImagen()
    {
        return $this->imagen;
    }

    public function setImagen(Imagen $imagen)
    {
        $this->imagen = $imagen;
        return $this;
    }

    public function setFechaPublicacion($fecha_publicacion = null)
    {

        if ($fecha_publicacion != null) {

            if (!($fecha_publicacion instanceof DateTime)) {
                $fecha_publicacion = DateTime::createFromFormat(config('simplecms.formats.just-date'), $fecha_publicacion);
            }
        }

        $this->fecha_publicacion = $fecha_publicacion;
        return $this;
    }

    public function setCreatedAt(DateTime $created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function setUpdatedAt(DateTime $updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->updated_at = new DateTime;
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
        return get_class($this) . $this->getId();
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
