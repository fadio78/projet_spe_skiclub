<?php

namespace SC\ActiviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Activite
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SC\ActiviteBundle\Entity\ActiviteRepository")
 */
class Activite
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nomactivite", type="string", length=255)
     */
    private $nomactivite;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="prixactivite", type="integer")
     */
    private $prixactivite;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="SC\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false, name="email",referencedColumnName="email")
     * 
     */
    private $user; 


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nomactivite
     *
     * @param string $nomactivite
     * @return Activite
     */
    public function setNomactivite($nomactivite)
    {
        $this->nomactivite = $nomactivite;

        return $this;
    }

    /**
     * Get nomactivite
     *
     * @return string 
     */
    public function getNomactivite()
    {
        return $this->nomactivite;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Activite
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set prixactivite
     *
     * @param integer $prixactivite
     * @return Activite
     */
    public function setPrixactivite($prixactivite)
    {
        $this->prixactivite = $prixactivite;

        return $this;
    }

    /**
     * Get prixactivite
     *
     * @return integer 
     */
    public function getPrixactivite()
    {
        return $this->prixactivite;
    }


    /**
     * Set user
     *
     * @param \SC\UserBundle\Entity\User $user
     * @return Activite
     */
    public function setUser(\SC\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \SC\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
