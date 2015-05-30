<?php

namespace SC\ParticipationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SC\ActiviteBundle\Entity\Sortie;
use SC\UserBundle\Entity\Enfant;

/**
 * InscriptionSortie
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SC\ParticipationBundle\Entity\InscriptionSortieRepository")
 */
class InscriptionSortie
{

    /**
     * @var integer
     * @ORM\Column(name="participation", type="integer")
     */
    private $participation;
    
    /**
     * @var string
     * @ORM\Column(name="dateSortie", type="string")
     * @ORM\Id
     */    
    private $dateSortie;

    /**
     * @var integer
     * @ORM\Column(name="idActivite", type="integer")
     * @ORM\Id
     */    
    private $idAcvitie;  
    
    /**
     * @var string
     * @ORM\Column(name="email", type="string")
     * @ORM\Id
     */    
    private $emailParent;
    
    /**
     * @var string
     * @ORM\Column(name="nomEnfant", type="string")
     * @ORM\Id
     */    
    private $nomEnfant;     

    /**
     * @var string
     * @ORM\Column(name="prenomEnfant", type="string")
     * @ORM\Id
     */    
    private $prenomEnfant;     
 

    /**
     * Set participation
     *
     * @param integer $participation
     * @return InscriptionSortie
     */
    public function setParticipation($participation)
    {
        $this->participation = $participation;

        return $this;
    }

    /**
     * Get participation
     *
     * @return integer 
     */
    public function getParticipation()
    {
        return $this->participation;
    }

    /**
     * Set dateSortie
     *
     * @param string $dateSortie
     * @return InscriptionSortie
     */
    public function setDateSortie($dateSortie)
    {
        $this->dateSortie = $dateSortie;

        return $this;
    }

    /**
     * Get dateSortie
     *
     * @return string 
     */
    public function getDateSortie()
    {
        return $this->dateSortie;
    }

    /**
     * Set idAcvitie
     *
     * @param integer $idAcvitie
     * @return InscriptionSortie
     */
    public function setIdAcvitie($idAcvitie)
    {
        $this->idAcvitie = $idAcvitie;

        return $this;
    }

    /**
     * Get idAcvitie
     *
     * @return integer 
     */
    public function getIdAcvitie()
    {
        return $this->idAcvitie;
    }

    /**
     * Set emailParent
     *
     * @param string $emailParent
     * @return InscriptionSortie
     */
    public function setEmailParent($emailParent)
    {
        $this->emailParent = $emailParent;

        return $this;
    }

    /**
     * Get emailParent
     *
     * @return string 
     */
    public function getEmailParent()
    {
        return $this->emailParent;
    }

    /**
     * Set nomEnfant
     *
     * @param string $nomEnfant
     * @return InscriptionSortie
     */
    public function setNomEnfant($nomEnfant)
    {
        $this->nomEnfant = $nomEnfant;

        return $this;
    }

    /**
     * Get nomEnfant
     *
     * @return string 
     */
    public function getNomEnfant()
    {
        return $this->nomEnfant;
    }

    /**
     * Set prenomEnfant
     *
     * @param string $prenomEnfant
     * @return InscriptionSortie
     */
    public function setPrenomEnfant($prenomEnfant)
    {
        $this->prenomEnfant = $prenomEnfant;

        return $this;
    }

    /**
     * Get prenomEnfant
     *
     * @return string 
     */
    public function getPrenomEnfant()
    {
        return $this->prenomEnfant;
    }
}
