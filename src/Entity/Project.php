<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity()
 * @ORM\Table(name="yaaannback_projects")
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @JMS\Groups({"projects", "project_detail"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=APIUser::class, inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     * @JMS\Groups({"projects", "project_detail"})
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(min=2, max=100)
     * @Assert\Url
     * @JMS\Groups({"project_detail"})
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(min=2, max=100)
     * @Assert\Url
     * @JMS\Groups({"project_detail"})
     */
    private $githubUrl;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(min=2, max=100)
     * @Assert\Url
     * @JMS\Groups({"project_detail"})
     */
    private $gitlabUrl;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @JMS\Groups({"project_detail"})
     */
    private $pictures = [];

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\Length(min=10, max=150)
     * @JMS\Groups({"project_detail"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(min=5, max=50)
     * @JMS\Groups({"projects", "project_detail"})
     */
    private $shortTitle;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=10, max=1000)
     * @JMS\Groups({"project_detail"})
     */
    private $tldr;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=20, max=20000)
     * @JMS\Groups({"project_detail"})
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=ProjectCathegory::class, inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     * @JMS\Groups({"projects", "project_detail"})
     */
    private $cathegory;

    /**
     * @ORM\Column(type="datetime")
     * @JMS\Groups({"projects", "project_detail"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @JMS\Groups({"projects", "project_detail"})
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?APIUser
    {
        return $this->author;
    }

    public function setAuthor(?APIUser $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getGithubUrl(): ?string
    {
        return $this->githubUrl;
    }

    public function setGithubUrl(?string $githubUrl): self
    {
        $this->githubUrl = $githubUrl;

        return $this;
    }

    public function getGitlabUrl(): ?string
    {
        return $this->gitlabUrl;
    }

    public function setGitlabUrl(?string $gitlabUrl): self
    {
        $this->gitlabUrl = $gitlabUrl;

        return $this;
    }

    public function getPictures(): ?array
    {
        return $this->pictures;
    }

    public function setPictures(?array $pictures): self
    {
        $this->pictures = $pictures;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getShortTitle(): ?string
    {
        return $this->shortTitle;
    }

    public function setShortTitle(string $shortTitle): self
    {
        $this->shortTitle = $shortTitle;

        return $this;
    }

    public function getTldr(): ?string
    {
        return $this->tldr;
    }

    public function setTldr(string $tldr): self
    {
        $this->tldr = $tldr;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of createdAt
     */ 
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     *
     * @return  self
     */ 
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of updatedAt
     */ 
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt
     *
     * @return  self
     */ 
    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get the value of cathegory
     */ 
    public function getCathegory()
    {
        return $this->cathegory;
    }

    /**
     * Set the value of cathegory
     *
     * @return  self
     */ 
    public function setCathegory($cathegory)
    {
        $this->cathegory = $cathegory;

        return $this;
    }
}
