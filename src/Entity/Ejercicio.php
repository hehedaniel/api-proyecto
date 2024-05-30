<?php

namespace App\Entity;

use App\Repository\EjercicioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EjercicioRepository::class)
 */
class Ejercicio
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
    private $grupoMuscular;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dificultad;

    /**
     * @ORM\Column(type="text")
     */
    private $instrucciones;

    /**
     * @ORM\Column(type="integer")
     */
    private $valorMET;

    /**
     * @ORM\OneToMany(targetEntity=Enlace::class, mappedBy="idEjercicio", orphanRemoval=true)
     */
    private $enlaces;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class, inversedBy="ejercicios")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idUsuario;

    public function __construct()
    {
        $this->enlaces = new ArrayCollection();
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

    public function getGrupoMuscular(): ?string
    {
        return $this->grupoMuscular;
    }

    public function setGrupoMuscular(string $grupoMuscular): self
    {
        $this->grupoMuscular = $grupoMuscular;

        return $this;
    }

    public function getDificultad(): ?string
    {
        return $this->dificultad;
    }

    public function setDificultad(string $dificultad): self
    {
        $this->dificultad = $dificultad;

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

    public function getValorMET(): ?int
    {
        return $this->valorMET;
    }

    public function setValorMET(int $valorMET): self
    {
        $this->valorMET = $valorMET;

        return $this;
    }

    /**
     * @return Collection<int, Enlace>
     */
    public function getEnlaces(): Collection
    {
        return $this->enlaces;
    }

    public function addEnlace(Enlace $enlace): self
    {
        if (!$this->enlaces->contains($enlace)) {
            $this->enlaces[] = $enlace;
            $enlace->setIdEjercicio($this);
        }

        return $this;
    }

    public function removeEnlace(Enlace $enlace): self
    {
        if ($this->enlaces->removeElement($enlace)) {
            // set the owning side to null (unless already changed)
            if ($enlace->getIdEjercicio() === $this) {
                $enlace->setIdEjercicio(null);
            }
        }

        return $this;
    }

    public function getIdUsuario(): ?Usuario
    {
        return $this->idUsuario;
    }

    public function setIdUsuario(?Usuario $idUsuario): self
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }
}
