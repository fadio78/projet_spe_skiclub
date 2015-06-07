<?php
// src/SC/ActiviteBundle/Controller/InscriptionActiviteController.php
namespace SC\ActiviteBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use SC\ActiviteBundle\Entity\Activite;
use SC\UserBundle\Entity\User;
use SC\UserBundle\Entity\Adhesion;
use SC\LicenceBundle\Entity\Licence;
use SC\ActiviteBundle\Entity\Saison;
use SC\ActiviteBundle\Entity\SaisonRepository;
use SC\ActiviteBundle\Entity\InscriptionActivite;
use SC\UserBundle\Entity\Enfant;
use Doctrine\ORM\EntityRepository;
use SC\UserBundle\Entity\EnfantRepository;
use SC\UserBundle\Entity\LicenceEnfant;





class InscriptionActiviteController extends Controller 
{

    
    public function inscriptionActiviteAction($id,Request $request) 
    {   
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $email = $session->get('email');   
        $inscriptionActivite = new InscriptionActivite();
        $activite = $em->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
        if (null === $activite) {
          throw new NotFoundHttpException("L'activité d'id ".$id." n'existe pas.");
        }
        $inscriptionActivite -> setActivite($activite);
        $inscriptionActivite -> setEmail($email);
        $saison = new Saison ();
        $year = $saison->connaitreSaison();  
        $saison = $em->getRepository('SC\ActiviteBundle\Entity\Saison')->find($year);
        if (null === $saison) {
          throw new NotFoundHttpException("La saison  d'année ".$annee." n'existe pas.");
        }
        $inscriptionActivite -> setSaison($saison);
        $defaultData = array('message' => 'Type your message here');
        $form = $this->createFormBuilder($defaultData)
           ->add('Enfant', 'entity', array('class'=> 'SC\UserBundle\Entity\Enfant','property' => 'prenomNom', 'multiple' => false,'expanded' => false,'required' => true, 'query_builder' => function (EnfantRepository $repository) use ($email) { return $repository->
            getEnfant($email); },))
          ->add('enregistrer','submit')
          ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()){
            $data = $form->getData();
            $enfant = $data ['Enfant'];
            $inscriptionActivite ->setNomEnfant($enfant -> getNomEnfant());
            $inscriptionActivite ->setPrenomEnfant($enfant -> getPrenomEnfant());
          
            $er = $em ->getRepository('SC\ActiviteBundle\Entity\InscriptionActivite');
            $inscri = $er->est_Inscrit($inscriptionActivite);
            if ($inscri == null)
            {
                $r = $em -> getRepository('SC\UserBundle\Entity\LicenceEnfant');
                $licence = $activite -> getLicence();
                if (isset($licence))
                {
                    // On ajoute une licence à l'enfant s'il ne la possède pas
                    $licenceEnfantExiste = $r -> findOneBy(array('email' => $email,'prenomEnfant' => ($enfant -> getPrenomEnfant()),'nomEnfant' => ($enfant -> getNomEnfant()),'saison' => $saison,'licence' => $activite ->getLicence() ));
                    if ($licenceEnfantExiste == null)
                    {
                        $this -> ajoutLicenceEnfant($licence,$enfant -> getPrenomEnfant(),$enfant -> getNomEnfant(),$saison,$email);
                        
                    }
                }    
                $em->persist($inscriptionActivite);
                $em->flush();
                $request->getSession()->getFlashBag()->add('info', 'Inscription bien enregistrée');
                return $this->redirect($this->generateUrl('sc_activite_homepage'));
            }
          
            else 
            {
                $request->getSession()->getFlashBag()->add('info', 'Enfant déjà inscrit');
                return $this->render('SCActiviteBundle:InscriptionActivite:addinscriactivite.html.twig', array(
            'form' => $form->createView(),
            ));
           }
       }
            return $this->render('SCActiviteBundle:InscriptionActivite:addinscriactivite.html.twig', array(
            'form' => $form->createView(),
            ));
    
    }
  
    //retourne la liste des inscriptions aux activités d'un utilisateur donné pour la saison en cours
    public function viewInscriActiviteAction (Request $request ) 
    {
        $prixLicence = null;
        $typeLicence = null;
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $email = $session->get('email');
        $r = $em -> getRepository('SC\ActiviteBundle\Entity\InscriptionActivite') ;
        $listeDeMesInscriptions = $r -> listeDeMesInscriptions($email); 
        $prix = $r -> getSommeApayer($email);
        foreach ( $listeDeMesInscriptions as $inscrit )
        {
            $licence = $inscrit ->getActivite() ->getLicence();
            if ($licence == null )
            {
                $typeLicence[$inscrit -> getPrenomEnfant().$inscrit -> getNomEnfant().$inscrit -> getEmail().$inscrit ->getActivite()->getNomActivite().$inscrit ->getSaison() -> getAnnee()] ="";
                $prixLicence[$inscrit -> getPrenomEnfant().$inscrit -> getNomEnfant().$inscrit -> getEmail().$inscrit ->getActivite()->getNomActivite(). $inscrit ->getSaison() -> getAnnee()] = 0;
            }
            else
            {
                $typeLicence[$inscrit -> getPrenomEnfant().$inscrit -> getNomEnfant().$inscrit -> getEmail().$inscrit ->getActivite()->getNomActivite().$inscrit ->getSaison() -> getAnnee()] = $licence-> getTypeLicence();
                $prixLicence[$inscrit -> getPrenomEnfant().$inscrit -> getNomEnfant().$inscrit -> getEmail().$inscrit ->getActivite()->getNomActivite().$inscrit ->getSaison() -> getAnnee()] = $licence -> getPrixLicence();
            }
        }
        return $this->render('SCUserBundle:Security:viewinscriptionactivite.html.twig',array('listeDeMesInscriptions' => $listeDeMesInscriptions,'prix' => $prix,'typeLicence' =>$typeLicence,'prixLicence' =>$prixLicence
        ));
    }

   
    public function ajoutLicenceEnfant($licence,$prenom,$nom,$saison,$email)
    {
        $em = $this ->getDoctrine() ->getManager();
        $licenceEnfant = new LicenceEnfant();
        $licenceEnfant -> setLicence ($licence);
        $licenceEnfant -> setEmail($email);
        $licenceEnfant -> setPrenomEnfant($prenom);
        $licenceEnfant -> setNomEnfant($nom); 
        $licenceEnfant -> setSaison($saison);
        $em->persist($licenceEnfant);
        
    }
    
    
}