<?php

namespace SC\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface ;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SC\UserBundle\Entity\UserRepository")
 */
class User implements AdvancedUserInterface
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * )
     */
    private $email;
    
    
        /**
     * @var string
     * 
     * @ORM\Column(name="emailPrimaire", type="string", length=255, nullable=true)
     * )
     */
    private $emailPrimaire;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var integer
     *
     * @ORM\Column(name="telephone", type="integer")
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="commune", type="string", length=255)
     */
    private $commune;

    /**
     * @var string
     *
     * 
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isPrimaire", type="boolean")
     */
    private $isPrimaire;

    /**
     * @var string
     * 
     * @ORM\Column(type="string", length=32)
     */
    private $salt;
    
  /*public function __construct($email)
    {
        //$this->isActive = true;
      //  $this->type = $roles;
        //$this->salt = md5(uniqid(null, true));
        $this->email= $email;
    }*/
    
    /**
   * @ORM\Column(name="roles", type="array", nullable=TRUE)
   
    private $roles = array();*/
    /**
     * Set emailPrimaire
     *
     * @param string $emailPrimaire
     * @return User
     */
    public function setEmailPrimaire($emailPrimaire)
    {
        $this->emailPrimaire = $emailPrimaire;

        return $this;
    }
       /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }
    
        /**
     * Get emailPrimaire
     *
     * @return string 
     */
    public function getEmailPrimaire()
    {
        return $this->emailPrimaire;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set telephone
     *
     * @param integer $telephone
     * @return User
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return integer 
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     * @return User
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string 
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set commune
     *
     * @param string $commune
     * @return User
     */
    public function setCommune($commune)
    {
        $this->commune = $commune;

        return $this;
    }

    /**
     * Get commune
     *
     * @return string 
     */
    public function getCommune()
    {
        return $this->commune;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return User
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set isActive
     *
     * @param boolean $IsActive
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }
        /**
     * Set isPrimaire
     *
     * @param boolean $isPrimaire
     * @return User
     */
    public function setIsPrimaire($isPrimaire)
    {
        $this->isPrimaire = $isPrimaire;

        return $this;
    }
    
    /**
     * Get validite
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }
        /**
     * Get isPrimaire
     *
     * @return boolean 
     */
    public function getIsPrimaire()
    {
        return $this->isPrimaire;
    }
    
    //Afin de pouvoir utiliser l'interface user on implemente ces méthodes
    
    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return array('ROLE_USER');
        
    }
    
    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }
    
     /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return $this->salt;
    }
    
    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->email;
    }
    /**
     * @inheritDoc
     */
    public function equals(UserInterface $user){
       
        if( $this->email === $user->getUsername()){
           return true;
        }
        return false;
    }
     public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Set roles
     *
     * @param array $roles
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    
}
