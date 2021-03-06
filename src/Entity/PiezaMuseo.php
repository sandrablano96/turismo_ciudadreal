<?php

namespace App\Entity;

use App\Repository\PiezasMuseoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PiezasMuseoRepository::class)]
class PiezaMuseo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $titulo;

    #[ORM\Column(type: 'text', nullable: true)]
    private $descripcion;

    #[ORM\Column(type: 'string', length: 255)]
    private $imagen;

    #[ORM\ManyToOne(targetEntity: Museo::class, inversedBy: 'piezas')]
    #[ORM\JoinColumn(nullable: false)]
    private $museo;

    #[ORM\Column(type: 'string', length: 100)]
    private $epoca;

    #[ORM\Column(type: 'string', length: 36)]
    private $uid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

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

    public function getMuseo(): ?Museo
    {
        return $this->museo;
    }

    public function setMuseo(?Museo $museo): self
    {
        $this->museo = $museo;

        return $this;
    }

    public function getEpoca(): ?string
    {
        return $this->epoca;
    }

    public function setEpoca(string $epoca): self
    {
        $this->epoca = $epoca;

        return $this;
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(string $uid): self
    {
        $this->uid = $uid;

        return $this;
    }
}
