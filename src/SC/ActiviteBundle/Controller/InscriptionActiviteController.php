<?php
// src/SC/ActiviteBundle/Controller/InscriptionActiviteController.php
namespace SC\ActiviteBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use SC\ActiviteBundle\Entity\Activite;
use SC\UserBundle\Entity\User;
use SC\UserBundle\Entity\Licence;
use SC\ActiviteBundle\Entity\Saison;
use SC\ActiviteBundle\Entity\SaisonRepository;
use SC\ActiviteBundle\Entity\InscriptionActivite;
use SC\UserBundle\Entity\Enfant;
use Doctrine\ORM\EntityRepository;
use SC\UserBundle\Entity\EnfantRepository;




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
        $inscriptionActivite  -> setLicenceValide(0);
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
  
    
    public function viewInscriActiviteAction (Request $request ) 
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $email = $session->get('email');
        $r = $em -> getRepository('SC\ActiviteBundle\Entity\InscriptionActivite') ;
        $listeDeMesInscriptions = $r -> listeDeMesInscriptions($email); 
        $prix = $r -> getSommeApayer($email);
        return $this->render('SCActiviteBundle:InscriptionActivite:viewinscription.html.twig',array('listeDeMesInscriptions' => $listeDeMesInscriptions,'prix' => $prix
        ));
        
    }

    
    
    
}