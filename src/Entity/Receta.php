<?php

namespace App\Entity;

use App\Repository\RecetaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RecetaRepository::class)
 */
class Receta
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="text")
     */
    private $descripcion;

    /**
     * @ORM\Column(type="text")
     */
    private $instrucciones;

    /**
     * @ORM\Column(type="float")
     */
    private $cantidadFinal;

    /**
     * @ORM\Column(type="float")
     */
    private $proteinas;

    /**
     * @ORM\Column(type="float")
     */
    private $grasas;

    /**
     * @ORM\Column(type="float")
     */
    private $carbohidratos;

    /**
     * @ORM\Column(type="float")
     */
    private $azucares;

    /**
     * @ORM\Column(type="float")
     */
    private $vitaminas;

    /**
     * @ORM\Column(type="float")
     */
    private $minerales;

    /**
     * @ORM\Column(type="text")
     */
    private $imagen;

    /**
     * @ORM\ManyToMany(targetEntity=Usuario::class, mappedBy="receta")
     */
    private $usuariosTomadores;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class, inversedBy="recetaRegistrada")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuarioCreador;

    public function __construct()
    {
        $this->usuariosTomadores = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getInstrucciones(): ?string
    {
        return $this->instrucciones;
    }

    public function setInstrucciones(string $instrucciones): self
    {
        $this->instrucciones = $instrucciones;

        return $this;
    }

    public function getCantidadFinal(): ?float
    {
        return $this->cantidadFinal;
    }

    public function setCantidadFinal(float $cantidadFinal): self
    {
        $this->cantidadFinal = $cantidadFinal;

        return $this;
    }

    public function getProteinas(): ?float
    {
        return $this->proteinas;
    }

    public function setProteinas(float $proteinas): self
    {
        $this->proteinas = $proteinas;

        return $this;
    }

    public function getGrasas(): ?float
    {
        return $this->grasas;
    }

    public function setGrasas(float $grasas): self
    {
        $this->grasas = $grasas;

        return $this;
    }

    public function getCarbohidratos(): ?float
    {
        return $this->carbohidratos;
    }

    public function setCarbohidratos(float $carbohidratos): self
    {
        $this->carbohidratos = $carbohidratos;

        return $this;
    }

    public function getAzucares(): ?float
    {
        return $this->azucares;
    }

    public function setAzucares(float $azucares): self
    {
        $this->azucares = $azucares;

        return $this;
    }

    public function getVitaminas(): ?float
    {
        return $this->vitaminas;
    }

    public function setVitaminas(float $vitaminas): self
    {
        $this->vitaminas = $vitaminas;

        return $this;
    }

    public function getMinerales(): ?float
    {
        return $this->minerales;
    }

    public function setMinerales(float $minerales): self
    {
        $this->minerales = $minerales;

        return $this;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * @return Collection<int, Usuario>
     */
    public function getUsuariosTomadores(): Collection
    {
        return $this->usuariosTomadores;
    }

    public function addUsuariosTomadore(Usuario $usuariosTomadore): self
    {
        if (!$this->usuariosTomadores->contains($usuariosTomadore)) {
            $this->usuariosTomadores[] = $usuariosTomadore;
            $usuariosTomadore->addRecetum($this);
        }

        return $this;
    }

    public function removeUsuariosTomadore(Usuario $usuariosTomadore): self
    {
        if ($this->usuariosTomadores->removeElement($usuariosTomadore)) {
            $usuariosTomadore->removeRecetum($this);
        }

        return $this;
    }

    public function getUsuarioCreador(): ?Usuario
    {
        return $this->usuarioCreador;
    }

    public function setUsuarioCreador(?Usuario $usuarioCreador): self
    {
        $this->usuarioCreador = $usuarioCreador;

        return $this;
    }
}
