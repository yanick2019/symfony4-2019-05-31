<?php

// src/Entity/User.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $role;

 
   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

     

    
    /**
     *  Returns the roles granted to the user.
     *  
     *         public function getRoles()
     *          {
     *              return array("ROLE_USER");
     *          }
     *  Alternatively, the roles might be stored on a ``roles`` property,
     *  and populated in any number of different ways when the user object
     *  is created
     * 
     *  @return (Role|string)[] the user roles
     */
    public function getRoles()
    {
        $role = $this->getRole();
        $roles = [] ;
       // return ['ROLE_ADMIN'];
        $roles[] = $role ;

        return array_unique($roles);
    }

    /**
     * Return the salt that was originally used to encode the password
     * this can return null if the password was not encoded using a salt.
     * 
     * @return string:null The salt
     */
    public function getSalt()
    {
         return null ; 
    }
    /**
     * Removes senstive data from the user.
     * 
     * This is important if , at any given point , sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        //TODO: Implement eraseCredentials() method 
         
    }
    /**
     * String representation of object
     * @return string the string representation of the object or null
     * 
     */

    public function serialize()
    {
        return serialize([
            $this->id , 
            $this->username,
            $this->password,
            
        ]);
        
    }
    /**
     * Constructs the object
     * @link https://php.net/manual/en/serializable.unserrialize.php
     * @param string @serrialized <p>
     * The string representation of the object
     * <p>
     * @return void
      */
    public function unserialize($serialized)
    {
        
        list(
            $this->id , 
            $this->username,
            $this->password
        ) = unserialize($serialized,['allowed_classes' => false ]);
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

}
