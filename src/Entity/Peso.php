<?php

namespace App\Entity;

use App\Repository\PesoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PesoRepository::class)
 */
class Peso
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $fecha;

    /**
     * @ORM\Column(type="float")
     */
    private $peso;

    /**
     * @ORM\Column(type="float")
     */
    private $imc;

    /**
     * @ORM\OneToOne(targetEntity=Usuario::class, inversedBy="ultimoPeso", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $idUsuario;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getPeso(): ?float
    {
        return $this->peso;
    }

    public function setPeso(float $peso): self
    {
        $this->peso = $peso;

        return $this;
    }

    public function getImc(): ?float
    {
        return $this->imc;
    }

    public function setImc(float $imc): self
    {
        $this->imc = $imc;

        return $this;
    }

    public function getIdUsuario(): ?Usuario
    {
        return $this->idUsuario;
    }

    public function setIdUsuario(Usuario $idUsuario): self
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }
}
