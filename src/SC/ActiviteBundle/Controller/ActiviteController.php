<?php
// src/SC/ActiviteBundle/Controller/ActiviteController.php
namespace SC\ActiviteBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use SC\ActiviteBundle\Entity\Activite;
use SC\ActiviteBundle\Form\ActiviteType;

use SC\ActiviteBundle\Form\LieuType;
use SC\ActiviteBundle\Form\SortieType;

use SC\ActiviteBundle\Form\ActiviteEditType;

use SC\UserBundle\Entity\User;
use SC\UserBundle\Entity\Licence;
use SC\ActiviteBundle\Entity\Sortie;
use SC\ActiviteBundle\Entity\Lieu;
use SC\ActiviteBundle\Entity\Saison;
use SC\ActiviteBundle\Entity\SaisonRepository;
use SC\ActiviteBundle\Entity\InscriptionActivite;
use SC\UserBundle\Entity\Enfant;
use Doctrine\ORM\EntityRepository;
use SC\UserBundle\Entity\EnfantRepository;
use SC\ActiviteBundle\Entity\ActiviteRepository;
use SC\ActiviteBundle\Entity\InscriptionActiviteRepository;


class ActiviteController extends Controller 
{
    
    public function indexAction(Request $request)
    {
        $em = $this -> getDoctrine() ->getManager();
        $season = new Saison;
        $year = $season->connaitreSaison();
        $request->getSession()->set('year', $year);
        $saison = $em -> getRepository('SCActiviteBundle:Saison') -> find($year);
        
        if (null === $saison) {
            $saison = new Saison();
            $saison->setAnnee($year);
            $em->persist($saison);
            $em->flush();
        }

    // On ne sait pas combien de pages il y a
    // Mais on sait qu'une page doit être supérieure ou égale à 1
       /*if ($page < 1) {
      // On déclenche une exception NotFoundHttpException, cela va afficher
      // une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        } */
      // Ici, on récupérera la liste des activités d'une saison donnée, puis on la passera au template

        $listeActivites =$saison -> getActivites();        
        return $this->render('SCActiviteBundle:Activite:index.html.twig',array('listeActivites' => $listeActivites,'year' =>$year
        ));
    }
    
    
    
    
    
    public function viewAction($id,Request $request)
    {
        // Ici, on récupérera l'activité correspondante à l'id $id
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCActiviteBundle:Activite');
        $activite = $repository->find($id); 
        if (null === $activite) {
          throw new NotFoundHttpException("L'activité d'id ".$id." n'existe pas.");
        }
        return $this->render('SCActiviteBundle:Activite:view.html.twig', array('activite' => $activite));
    }
    
    
    
    
    
    public function addAction(Request $request)
    {
        // On récupère à partir de la session l'utilisateur
        $session = $request->getSession();
        $email = $session->get('email');

        $user = $this->getDoctrine()->getManager()->getRepository('SC\UserBundle\Entity\User')->find($email);
        // On crée un objet Activite
        $activite = new Activite();
        $activite->setUser($user);
        $form = $this->get('form.factory')->create(new ActiviteType(), $activite);
        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $activite contient les valeurs entrées dans le formulaire par l'e visiteur l'admin
        $form->handleRequest($request);


        $season = new Saison;
        $year = $season->connaitreSaison();
        $user = $this->getDoctrine()->getManager()->getRepository('SC\UserBundle\Entity\User')->find($email);
            // On crée un objet Activite
            $activite = new Activite();
            $activite->setUser($user);
            $form = $this->get('form.factory')->create(new ActiviteType(), $activite);
            // On fait le lien Requête <-> Formulaire
            // À partir de maintenant, la variable $activite contient les valeurs entrées dans le formulaire par l'e visiteur l'admin
            $form->handleRequest($request);
            // On vérifie que les valeurs entrées sont correctes
            if ($form->isValid()) {
                $saison = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Saison')->find($year);
                $saison -> addActivite($activite);

            // On l'enregistre notre objet $activite dans la base de données, par exemple
            $em = $this->getDoctrine()->getManager();
            $em->persist($activite);
            $em->flush();
            $request->getSession()->getFlashBag()->add('info', 'Activité bien enregistrée');
            // On redirige vers la page de visualisation de l'activité nouvellement créée
            return $this->redirect($this->generateUrl('sc_activite_view', array('id' => $activite->getId())));
        }
        // À ce stade, le formulaire n'est pas valide car :
        // - Soit la requête est de type GET, donc l'admin vient d'arriver sur la page et veut voir le formulaire
        // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
        return $this->render('SCActiviteBundle:Activite:add.html.twig', array('form' => $form->createView()
        ));
        
         
    
    }
  
 
  
 

    public function editAction($id, Request $request)
    {
     
        $em = $this->getDoctrine()->getManager();

        // On récupère l'activité $id
        $activite = $em->getRepository('SCActiviteBundle:Activite')->find($id);

        if (null === $activite) {
            throw new NotFoundHttpException("L'activité d'id ".$id." n'existe pas.");
        }

        $form = $this->createForm(new ActiviteEditType(), $activite);

        if ($form->handleRequest($request)->isValid()) {
        // Inutile de persister ici, Doctrine connait déjà notre activité
            $em->flush();
            $request->getSession()->getFlashBag()->add('info', 'Activité bien modifiée');
            return $this->redirect($this->generateUrl('sc_activite_view', array('id' => $activite->getId())));
        }

        return $this->render('SCActiviteBundle:Activite:edit.html.twig', array('form'   => $form->createView(),'activite' => $activite));// Je passe également l'activité à la vue si jamais elle veut l'afficher))
    }
    
  
  
  
    public function deleteAction($id,Request $request)
    {

        $season = new Saison;
        $year = $season->connaitreSaison();
        $session = $request->getSession();

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('SCActiviteBundle:Activite');
        $activite = $repository->find($id);
        //si n'existe pas -> message d'erreur
        if (is_null($activite)) {
            $response = new Response;
            $response->setContent("Error 404: not found");
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            return $response;          
        }
        else {
            $nomActivite = $activite -> getNomactivite();
            // message envoyé en cas d'annulation de l'activité
            $msg = "Veuillez nous excusez, l''activité " . $nomActivite ." est annulée";
            $saison = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Saison')->find($year);
            $repository = $em -> getRepository('SC\ActiviteBundle\Entity\InscriptionActivite');
            // liste des inscrits à l'activité cette saison
            $inscriptions = $repository-> inscriptions($id);
        //    $inscriptions = $repository ->findBy(array('activite' => $activite,'saison' => $saison));
            // liste des inscrits à l'activité des saisons précédentes
            $inscriptionsSaisons = $repository -> inscriptionsSaisons($id);
            $saison -> removeActivite($activite);
         /*   if (is_null($inscriptions) == true) {
                $em->remove($activite);
            }*/
            $saisonactuelle  = null;
            $saisonsprecedentes = null;
            
            foreach ($inscriptions as $inscription) 
            {
              $saisonactuelle = $inscription -> getEmail();
            }
            foreach ($inscriptionsSaisons as $inscription) 
            {
              $saisonsprecedentes = $inscription -> getEmail();
            }
            //je supprime l'activité s'il n'y a pas d'inscription
            if (is_null($saisonactuelle) AND is_null($saisonsprecedentes) )
            {
                
                $em->remove($activite);
            }
            //je supprime l'activité s'il y a des inscriptions que cette saison
            if (is_null($saisonsprecedentes)  AND   isset($saisonactuelle))
            {
                //envoi des mails aux inscrits
                foreach ($inscriptions as $inscription)
                    $message = \Swift_Message::newInstance()
                      ->setSubject('Compte activé')
                      ->setFrom($request->getSession()->get('email'))
                      ->setTo($inscription ->getEmail())
                      ->setBody($msg);
                    $this->get('mailer')->send($message);
                //je supprime les inscriptions de cette saison
                    $em ->remove($inscription);
                }
                $em->remove($activite);
            }
            // je ne supprime que les inscriptions de cette saison 
            //s'il y a des inscriptions cette saison et les saisons précédentes  
            if ( isset($saisonsprecedentes) AND isset($saisonactuelle))
            {

                foreach ($inscriptions as $inscription)
            {
                // envoi des mails
                $message = \Swift_Message::newInstance()
                    ->setSubject('Compte activé')
                    ->setFrom($request->getSession()->get('email'))
                    ->setTo($inscription ->getEmail())
                    ->setBody($msg);
                    $this->get('mailer')->send($message);
                $em ->remove($inscription);
            }
            }
       
            $this->suppSoritesEtInscrit($activite,$saison);
            $this->deleteStagesInscriptionStages($activite,$saison);
            
            $re = $em ->getRepository('SC\ActiviteBundle\Entity\InscriptionActivite');           

            $em->flush();
            $listeActivites =$saison -> getActivites();
            return $this->render('SCActiviteBundle:Activite:index.html.twig', array('listeActivites' => $listeActivites));

           
    }
    
    public function suppSoritesEtInscrit($activite,$saison) {
        $em = $this->getDoctrine()->getManager();
        $sorties = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Sortie')->findBy(array('activite'=> $activite,'saison'=>$saison));
            foreach ($sorties as $sortie) {
                $em->remove($sortie);
            }
        $inscrits = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\InscriptionSortie')->findBy(array('idActivite'=> $activite,'saison'=>$saison->getAnnee()));    
            foreach ($inscrits as $enfant) {
                $em->remove($enfant);
            }        
        $em->flush();
    }
    
    public function deleteStagesInscriptionStages($activite,$saison) {
        $em = $this->getDoctrine()->getManager();
        $stages = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Stage')->findBy(array('activite'=> $activite,'saison'=>$saison));
            foreach ($stages as $stage) {
                $em->remove($stage);
            }
        $inscriptionStages = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\InscriptionStage')->findBy(array('activite'=> $activite,'saison'=>$saison));    
            foreach ($inscrits as $enfant) {
                $em->remove($enfant);
            }        
        $em->flush();
    }
    

    public function ajoutSortieAction($id,Request $request) {
        
        $sortie = new Sortie();
        $sortie->setActivite($this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id));
            
        // On crée le FormBuilder grâce au service form factory
        $form = $this->get('form.factory')->createBuilder('form', $sortie)
          ->add('dateSortie','text')
          ->add('lieu','text')
          ->add('enregistrer','submit')
          ->getForm();
        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $activite contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);
        // On vérifie que les valeurs entrées sont correctes
        if ($form->isValid()) {
            // On l'enregistre notre objet $activite dans la base de données, par exemple
            $em = $this->getDoctrine()->getManager();
            $em->persist($sortie);
            $em->flush();
            return $this->redirect($this->generateUrl('sc_activite_view', array('id' => $activite->getId())));
        }
            return $this->render('SCActiviteBundle:Activite:add.html.twig', array(
            'form' => $form->createView(),
            ));
    }
    
    // permet de connaitre la saison courante
    public function connaitreSaison() {
        
        $date = new \DateTime();
        $annee = $date->format('Y');
        $mois = $date->format('m');
        
        if ($mois > 8) {
            return $annee;
        }
        else {
            return $annee-1;
        }
    }
    
    

}
