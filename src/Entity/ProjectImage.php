<?php

namespace App\Entity;

use ArrayIterator;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Type;

class ProjectImage extends ArrayIterator
{
    /**
     * @var string|null
     * @Type("string")
     */
    private $filename;

    /**
     * @var File|null
     * @Assert\NotBlank()
     * @Assert\File(
     *      maxSize = "2M",
     *      mimeTypes = {"image/jpeg", "image/jpg", "image/png"},
     *      mimeTypesMessage = "Please upload a valid image (jpeg, jpg, png)"
     * )
     */
    private $imageFile;


    /**
     * Get the value of filename
     *
     * @return  string|null
     */ 
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set the value of filename
     *
     * @param  string|null  $filename
     *
     * @return  self
     */ 
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get the value of imageFile
     *
     * @return  File|null
     */ 
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Set the value of imageFile
     *
     * @param  File|null  $imageFile
     *
     * @return  self
     */ 
    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;

        return $this;
    }
}
