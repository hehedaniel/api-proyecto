<?php

namespace App\Entity;

use App\Repository\EjercicioRepository;
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
     * @ORM\Column(type="float")
     */
    private $caloriasQuemadas;

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

    public function getCaloriasQuemadas(): ?float
    {
        return $this->caloriasQuemadas;
    }

    public function setCaloriasQuemadas(float $caloriasQuemadas): self
    {
        $this->caloriasQuemadas = $caloriasQuemadas;

        return $this;
    }
}
