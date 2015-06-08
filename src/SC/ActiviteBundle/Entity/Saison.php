<?php

namespace SC\ActiviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Saison
 *
 * @ORM\Table("SkiClub_Saison")
 * @ORM\Entity(repositoryClass="SC\ActiviteBundle\Entity\SaisonRepository")
 */
class Saison
{

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="annee", type="integer")
     */
    private $annee;
    
    
 
    /**
     * @ORM\ManyToMany(targetEntity="SC\ActiviteBundle\Entity\Activite",cascade={"remove"})
     * @ORM\JoinTable(name="SkiClub_Activites_Saisons",
     *      joinColumns={@ORM\JoinColumn(name="annee", referencedColumnName="annee")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id", referencedColumnName="id")}
     *      )
     **/

    private $activites;
    

    /**
     * Set annee
     *
     * @param integer $annee
     * @return Saison
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return integer 
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->activites = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add activites
     *
     * @param \SC\ActiviteBundle\Entity\Activite $activites
     * @return Saison
     */
    public function addActivite(\SC\ActiviteBundle\Entity\Activite $activite)
    {
        $this->activites[] = $activite;

        return $this;
    }

    /**
     * Remove activites
     *
     * @param \SC\ActiviteBundle\Entity\Activite $activites
     */
    public function removeActivite(\SC\ActiviteBundle\Entity\Activite $activite)
    {
        $this->activites->removeElement($activite);
    }

    /**
     * Get activites
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActivites()
    {
        return $this->activites;
    }
    
// permet de connaitre la saison courante
    public function connaitreSaison() {
        
        $date = new \DateTime();
        $annee = $date->format('Y');
        $mois = $date->format('m');
        //return 2015;
        if ($mois > 8) {
            return $annee;
        }
        else {
            return $annee-1;
        }
    }    

    public function __toString() {
        $res ='';
        foreach($this->activites as $act) {
        $res = $res.' '.$act->getNomActivite();
        }
        return $res;
        /*return $this->activites[0]->ge.' '.$this->activites[0];*/
    }


}
