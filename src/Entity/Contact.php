<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Type;

class Contact
{
    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Type("string")
     */
    private $email;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min=1, max=100)
     * @Type("string")
     */
    private $fullname;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min=1, max=100)
     * @Type("string")
     */
    private $subject;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min=10)
     * @Type("string")
     */
    private $content;

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
     * Get the value of fullname
     */ 
    public function getfullname()
    {
        return $this->fullname;
    }

    /**
     * Set the value of fullname
     *
     * @return self
     */ 
    public function setfullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get the value of content
     */ 
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @return self
     */ 
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of subject
     *
     * @return  string|null
     */ 
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set the value of subject
     *
     * @param  string|null  $subject
     *
     * @return  self
     */ 
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }
}
