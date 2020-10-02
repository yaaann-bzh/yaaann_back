<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface; 
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity()
 * @ORM\Table(name="yaaannback_user")
 * 
 * @JMS\ExclusionPolicy("all")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @JMS\Groups({"users", "user_detail"})
     * @JMS\Expose
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true, length=100)
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=50)
     * @JMS\Groups({"users", "user_detail"})
     * @JMS\Expose
     */
    private $username;

    /**
     * @ORM\Column(type="string", unique=true, length=100)
     * @Assert\Email()
     * @JMS\Groups({"user_detail"})
     * @JMS\Expose
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @JMS\Groups({"user_detail"})
     * @JMS\Expose  
     */
    private $roles;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Regex(
     *      pattern="/((?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])).{8,}/"
     * )
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Project::class, mappedBy="author")
     * @JMS\Groups({"user_detail"})
     * @JMS\Expose
     */
    private $projects;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // MySQL on my OVH host doesnt support json format : I cheat to convert string to an array
        if (is_string($roles)) {
            $roles = explode(',', str_replace(' ', '', $roles));
        }

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        // MySQL on my OVH host doesnt support json format : I cheat to convert string to an array
        $this->roles = str_replace('"', '', implode(', ', $roles));
        
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->setAuthor(null);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->contains($project)) {
            $this->projects->removeElement($project);
            // set the owning side to null (unless already changed)
            if ($project->getAuthor() === $this) {
                $project->setAuthor(null);
            }
        }

        return $this;
    }

    public function serialize()
    {
        serialize([
            $this->id,
            $this->username,
            $this->password
        ]);
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password
        ) = unserialize($serialized, ['allowed_classes' => false]);
    }
}
