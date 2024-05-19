<?php

namespace App\Entity;

use App\Repository\AlimentoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AlimentoRepository::class)
 */
class Alimento
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
     * @ORM\Column(type="string", length=255)
     */
    private $marca;

    /**
     * @ORM\Column(type="float")
     */
    private $cantidad;

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
     * @ORM\ManyToMany(targetEntity=Usuario::class, mappedBy="alimentos")
     */
    private $usuarios;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class, inversedBy="alimentosPropuestos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuarioProponedor;

    public function __construct()
    {
        $this->usuarios = new ArrayCollection();
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

    public function getMarca(): ?string
    {
        return $this->marca;
    }

    public function setMarca(string $marca): self
    {
        $this->marca = $marca;

        return $this;
    }

    public function getCantidad(): ?float
    {
        return $this->cantidad;
    }

    public function setCantidad(float $cantidad): self
    {
        $this->cantidad = $cantidad;

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
    public function getUsuarios(): Collection
    {
        return $this->usuarios;
    }

    public function addUsuario(Usuario $usuario): self
    {
        if (!$this->usuarios->contains($usuario)) {
            $this->usuarios[] = $usuario;
            $usuario->addAlimento($this);
        }

        return $this;
    }

    public function removeUsuario(Usuario $usuario): self
    {
        if ($this->usuarios->removeElement($usuario)) {
            $usuario->removeAlimento($this);
        }

        return $this;
    }

    public function getUsuarioProponedor(): ?Usuario
    {
        return $this->usuarioProponedor;
    }

    public function setUsuarioProponedor(?Usuario $usuarioProponedor): self
    {
        $this->usuarioProponedor = $usuarioProponedor;

        return $this;
    }
}
