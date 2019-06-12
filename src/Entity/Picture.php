<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
 use Symfony\Component\HttpFoundation\File\UploadedFile;
 
/**
 * @ORM\Entity(repositoryClass="App\Repository\PictureRepository")
 * @Vich\Uploadable
 */
class Picture
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *  
     *  
     * @ORM\Column(type="integer")
     */
    private $property_id ;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Property", inversedBy="pictures")
     * 
     */

    // @JoinColumn(name="property_id" , referenceColumnName="id")
    
    private $Property;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updateAt;


     /**
     * @Vich\UploadableField(mapping="property_image", fileNameProperty="path" ) 
     * 
     * @var File
     * @Assert\Image( mimeTypes = {"image/jpeg" ,   "image/jpg" ,  "image/gif",   "image/bmp",  "image/png" } , maxSize="10M" )
     */

    private $picFile;



    public function getId(): ?int
    {
        return $this->id;
    }
      


     public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getProperty(): ?Property
    {
        return $this->Property;
    }

    public function setProperty(?Property $Property): self
    {
        $this->Property = $Property;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getPicFile(): ?File
    {
        return $this->picFile;
    }
    public function setPicFile(?File $picFile = null     ): self
    {
        $this->picFile = $picFile;
             
        if ( $this->picFile instanceof UploadedFile && null !== $picFile  ) {
           
            $this->updateAt = new \DateTime('now');
         }
        return $this;
    }


}
