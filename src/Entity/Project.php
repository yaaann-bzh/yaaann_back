<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=APIUser::class, inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(min=2, max=100)
     * @Assert\Url
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(min=2, max=100)
     * @Assert\Url
     */
    private $githubUrl;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(min=2, max=100)
     * @Assert\Url
     */
    private $gitlabUrl;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $pictures = [];

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\Length(min=10, max=150)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(min=10, max=50)
     */
    private $shortTitle;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=10, max=300)
     */
    private $tldr;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=20, max=3000)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=ProjectCathegory::class, inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cathegory;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateDate;

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
     * Get the value of creationDate
     */ 
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set the value of creationDate
     *
     * @return  self
     */ 
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get the value of updateDate
     */ 
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * Set the value of updateDate
     *
     * @return  self
     */ 
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

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
