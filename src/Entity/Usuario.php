<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="string", length=255)
     */
    private $apellidos;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $correo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $correo_v;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contrasena;

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
     * @ORM\Column(type="string", length=255)
     */
    private $objetivo_opt;

    /**
     * @ORM\Column(type="float")
     */
    private $objetivo_num;

    /**
     * @ORM\OneToMany(targetEntity=Ejercicio::class, mappedBy="idUsuario")
     */
    private $ejercicios;

    public function __construct()
    {
        $this->ejercicios = new ArrayCollection();
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

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): self
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getCorreo(): ?string
    {
        return $this->correo;
    }

    public function setCorreo(string $correo): self
    {
        $this->correo = $correo;

        return $this;
    }

    public function getCorreoV(): ?string
    {
        return $this->correo_v;
    }

    public function setCorreoV(string $correo_v): self
    {
        $this->correo_v = $correo_v;

        return $this;
    }

    public function getContrasena(): ?string
    {
        return $this->contrasena;
    }

    public function setContrasena(string $contrasena): self
    {
        $this->contrasena = $contrasena;

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

    public function getObjetivoOpt(): ?string
    {
        return $this->objetivo_opt;
    }

    public function setObjetivoOpt(string $objetivo_opt): self
    {
        $this->objetivo_opt = $objetivo_opt;

        return $this;
    }

    public function getObjetivoNum(): ?float
    {
        return $this->objetivo_num;
    }

    public function setObjetivoNum(float $objetivo_num): self
    {
        $this->objetivo_num = $objetivo_num;

        return $this;
    }

    /**
     * @return Collection<int, Ejercicio>
     */
    public function getEjercicios(): Collection
    {
        return $this->ejercicios;
    }

    public function addEjercicio(Ejercicio $ejercicio): self
    {
        if (!$this->ejercicios->contains($ejercicio)) {
            $this->ejercicios[] = $ejercicio;
            $ejercicio->setIdUsuario($this);
        }

        return $this;
    }

    public function removeEjercicio(Ejercicio $ejercicio): self
    {
        if ($this->ejercicios->removeElement($ejercicio)) {
            // set the owning side to null (unless already changed)
            if ($ejercicio->getIdUsuario() === $this) {
                $ejercicio->setIdUsuario(null);
            }
        }

        return $this;
    }
}
