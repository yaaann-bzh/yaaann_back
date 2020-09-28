<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="yaaannback_user_connection")
 */
class UserConnection
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=APIUser::class, inversedBy="connections")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $connectDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?APIUser
    {
        return $this->user;
    }

    public function setUser(?APIUser $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getConnectDate(): ?\DateTimeInterface
    {
        return $this->connectDate;
    }

    public function setConnectDate(\DateTimeInterface $connectDate): self
    {
        $this->connectDate = $connectDate;

        return $this;
    }
}
