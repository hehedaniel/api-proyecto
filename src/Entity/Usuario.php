<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UsuarioRepository::class)
 */
class Usuario
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
     * @ORM\Column(type="boolean")
     */
    private $rol;

    /**
     * @ORM\Column(type="integer")
     */
    private $edad;

    /**
     * @ORM\Column(type="float")
     */
    private $altura;

    /**
     * @ORM\Column(type="float")
     */
    private $peso;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $objetivo_opt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $objetivo_num;

    /**
     * @ORM\OneToOne(targetEntity=Peso::class, mappedBy="idUsuario", cascade={"persist", "remove"})
     */
    private $ultimoPeso;

    /**
     * @ORM\OneToOne(targetEntity=ConsumoDia::class, mappedBy="idUsuario", cascade={"persist", "remove"})
     */
    private $consumoDia;

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

    public function isRol(): ?bool
    {
        return $this->rol;
    }

    public function setRol(bool $rol): self
    {
        $this->rol = $rol;

        return $this;
    }

    public function getEdad(): ?int
    {
        return $this->edad;
    }

    public function setEdad(int $edad): self
    {
        $this->edad = $edad;

        return $this;
    }

    public function getAltura(): ?float
    {
        return $this->altura;
    }

    public function setAltura(float $altura): self
    {
        $this->altura = $altura;

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

    public function getObjetivoOpt(): ?string
    {
        return $this->objetivo_opt;
    }

    public function setObjetivoOpt(string $objetivo_opt): self
    {
        $this->objetivo_opt = $objetivo_opt;

        return $this;
    }

    public function getObjetivoNum(): ?string
    {
        return $this->objetivo_num;
    }

    public function setObjetivoNum(string $objetivo_num): self
    {
        $this->objetivo_num = $objetivo_num;

        return $this;
    }

    public function getUltimoPeso(): ?Peso
    {
        return $this->ultimoPeso;
    }

    public function setUltimoPeso(Peso $ultimoPeso): self
    {
        // set the owning side of the relation if necessary
        if ($ultimoPeso->getIdUsuario() !== $this) {
            $ultimoPeso->setIdUsuario($this);
        }

        $this->ultimoPeso = $ultimoPeso;

        return $this;
    }

    public function getConsumoDia(): ?ConsumoDia
    {
        return $this->consumoDia;
    }

    public function setConsumoDia(ConsumoDia $consumoDia): self
    {
        // set the owning side of the relation if necessary
        if ($consumoDia->getIdUsuario() !== $this) {
            $consumoDia->setIdUsuario($this);
        }

        $this->consumoDia = $consumoDia;

        return $this;
    }
}
