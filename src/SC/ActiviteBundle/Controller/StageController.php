<?php
namespace SC\ActiviteBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use SC\ActiviteBundle\Entity\Stage;
use SC\ActiviteBundle\Entity\Activite;
use SC\ActiviteBundle\Entity\Saison;
use SC\ActiviteBundle\Form\StageType;
use SC\ActiviteBundle\Form\StageEditType;
use SC\UserBundle\Entity\User;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of StageController
 * Controleur pour la gestion des stages (ajout, suppression, etc...)
 *
 * @author kithr
 */
class StageController extends Controller {
  
    
    
    public function indexAction()
    {
      // On récupére la liste des stages, puis on la passera au template
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCActiviteBundle:Stage');
        $listeStages = $repository->findAll();  
        
        return $this->render('SCActiviteBundle:Stage:index.html.twig',array('listeStages' => $listeStages ));
    }
    
        public function viewAction($id) {
            
            $season = new Saison();
            $year = $season->connaitreSaison();
            $activite = $this->GetDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
            $saison = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Saison')->findOneByAnnee($year);
            if(is_null($activite) == false) {
                // on recupere tous les stages
                $listeStages = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Stage')->findBy(array('activite'=>$activite,'saison'  => $saison));
                return $this->render('SCActiviteBundle:Stage:view.html.twig', array('listeStages' => $listeStages, 'activite' => $activite));
            } else {
                $response = new Response;
                $response->setContent("Error 404: not found");
                $response->setStatusCode(Response::HTTP_NOT_FOUND);
                return $response;
            }
        }
    
    public function addAction($id,Request $request) {
        
        $stage = new Stage();
        $activite = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
        
        if (is_null($request->getSession()->get('email'))) {
            $response = new Response;
            $reponse->setContent("Vous devez vous connecter");
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            return $response;
        }
        
        if (is_null($activite)==false) {
            
            $stage = $stage->setActivite($activite);        
            $form = $this->get('form.factory')->create(new StageType(), $stage);
            $form->handleRequest($request);
            
            // On vérifie que les valeurs entrées sont correctes
            if ($form->isValid()) {
                
                $debut = $stage->getDebutStage();
                $fin = $stage->getFinStage();
                // On cast les dates : datetime -> String
                $stringDebut = $debut->format('Y').'-'.$debut->format('m').'-'.
                        $debut->format('d');
                $stringFin = $fin->format('Y').'-'.$fin->format('m').'-'.
                        $fin->format('d');
                $stage->setDebutStage($stringDebut);
                $stage->setFinStage($stringFin);
                
                // On vérifie que le stage n'existe pas déjà dans la BD
                $em = $this->getDoctrine()->getManager();
                $listeStages = $em->getRepository('SC\ActiviteBundle\Entity\Stage')->findAll();
                foreach($listeStages as $stageSeul) {
                    if ($stage->getActivite()->getId() == $stageSeul->getActivite()->getId()
                            && $stage->getDebutStage() == $stageSeul->getDebutStage()
                            && $stage->getFinStage() == $stageSeul->getFinStage()) {
                        $request->getSession()->getFlashBag()->add('info','Le stage existe déjà !');
                        return $this->redirect($this->generateUrl('sc_activite_addStage', array('id' => $activite->getId())));
                    }
                }
                
                // On récupère l'email de l'utilisateur en question
                $email = $request->getSession()->get('email');
                // On récupère l'utilisateur grâce à son email
                $user = $this->getDoctrine()->getManager()->getRepository('SC\UserBundle\Entity\User')->find($email);
                // On met l'utilisateur dans $stage
                $stage->setUser($user);
                
                $em->persist($stage);
                
                //si le lieu est n'est pas gere par le manager, on l'ajoute a la base
                $listLieu = $em->getRepository('SC\ActiviteBundle\Entity\Lieu')->findAll();
                $listeSaison = $em->getRepository('SC\ActiviteBundle\Entity\Saison')->findAll();
                
                //booleans pour savoir si on doit persist la variable en question
                $booleanLieu = false;
                $booleanSaison = false;
                
                foreach ($listLieu as $lieu) {
                    if ($lieu->getNomLieu() === $stage->getLieu()->getNomLieu()) {
                        $stage->setLieu($lieu);
                        // lieu existe déjà dans la base donc on n'aura pas besoin de le persist
                        $booleanLieu = true;
                    }
                }
                
                foreach ($listeSaison as $saison) {
                    if ($saison->getAnnee() === $stage->getSaison()->getAnnee()) {
                        $stage->setSaison($saison);
                        // saison existe déjà dans la base donc on n'aura pas besoin de le persist
                        $booleanSaison = true;
                    }
                }
                // Si le lieu n'existe pas , on le crée
                if ($booleanLieu === false) {                
                    // on persist lieu car il n'est pas dans la base
                    $em->persist($stage->getLieu());
                }
                // Si la saison n'existe pas, on la crée
                if ($booleanSaison === false) {
                    // on persist saison car il n'est pas dans la base
                    $em->persist($stage->getSaison());
                }
                
                $em->flush();
                $saison = new Saison();
                $year = $saison->connaitreSaison();
                $saison = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Saison')->findOneByAnnee($year);
                $listeStages2 = $em->getRepository('SC\ActiviteBundle\Entity\Stage')->findBy(array('saison'=>$saison));
                return $this->redirect($this->generateUrl('sc_activite_viewStage', 
                        array('id' => $stage->getActivite()->getId(), 'listeStages' => $listeStages2)));
            }
                return $this->render('SCActiviteBundle:Stage:add.html.twig', array(
                    'form' => $form->createView(),
                ));
        }
        else {
                $response = new Response;
                $response->setContent("Error 404: not found");
                $response->setStatusCode(Response::HTTP_NOT_FOUND);
                return $response;
        }
    }
    
    public function deleteAction($id, $debutStage, $finStage, Request $request) {
        
        $em = $this->getDoctrine()->getManager();
        $activite = $em->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
        // on verifie que les parametres sont bons
                $stage = $em->getRepository('SC\ActiviteBundle\Entity\Stage')
                ->findOneBy(array('activite' =>  $activite, 'debutStage'=> $debutStage,
                    'finStage'=>$finStage));
        
        if (isset($activite) === false || isset($stage) === false ) {
           $response = new Response;
           $response->setContent("Error 404: not found");
           $response->setStatusCode(Response::HTTP_NOT_FOUND);
           return $response; 
        }
            
        
        if (isset($stage) == FALSE) {
            $listeStages = $em->getRepository('SC\ActiviteBundle\Entity\Stage')->findAll();
            return $this->render('SCActiviteBundle:Stage:view.html.twig',array('listeStage' => $listeStages, 'activite' => $activite ));            
        }
        else {
            $request->getSession()->getFlashBag()->add('info', 'Le stage a bien été supprimé, '
                    . 'et un mail a été envoyé aux personnes inscrites');
            //envoyer les emails aux utilisateurs inscrits
            $em = $this->getDoctrine()->getManager();
            $saison = new Saison();
            $year = $saison->connaitreSaison();
            $saison = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Saison')->findOneByAnnee($year);
            
            // On supprime ici l'inscription de chaque enfant du stage qui va être supprimé
            $inscrits = $em->getRepository('SC\ActiviteBundle\Entity\InscriptionStage')
                            ->findBy(array('debutStage'=>$debutStage,'finStage'=>$finStage,'activite'=>$activite,'saison'=> $saison));
            foreach ($inscrits as $enfant) {
            $this->mailStageCancelled($enfant->getUser()->getEmail(), $debutStage, $finStage, $lieu);
            $em->remove($enfant);
            }
            // On supprime le stage
            $em->remove($stage);
            $em->flush();

            $listeStages = $em->getRepository('SC\ActiviteBundle\Entity\Stage')->findBy(array('activite' =>$activite, 'saison'=>$saison));
            return $this->render('SCActiviteBundle:Stage:view.html.twig',array('listeStages' => $listeStages, 'activite' => $activite ));
        }
    }
    // debutStage, finStage les dates du stage a modifier 
    public function editAction($id, $debutStage, $finStage, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $activite = $em->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
        
        
        
        $stage = $em->getRepository('SC\ActiviteBundle\Entity\Stage')->findOneBy(
                array('activite'=>$activite, 'debutStage'=>$debutStage, 'finStage'=> $finStage));

        
        if (isset($activite) === false || isset($stage) === false ) {
           $response = new Response;
           $response->setContent("Ekoijvrfjvrijvfijvoi");
           $response->setStatusCode(Response::HTTP_NOT_FOUND);
           return $response; 
        }
        $em->remove($stage);
        // A FAIRE 
        //$this->modifInscriptionStage($id, $debutStageAncien, $finStageAncien);
        $em->flush();
        
        $this->addAction($id, $request);
         
    }
    
    public function mailStageCreated($email, $debutStage, $finStage, $lieu) {
            
        $message = \Swift_Message::newInstance()                     
                            ->setSubject('SKICLUB : Stage programmé')
                            ->setFrom('sfr@hotmail.com')
                            ->setTo($email)
                            ->setBody('Bonjour, nous vous informons de la création d\'un stage prévue du '
                                    .$debutStage.' au '.$finStage.' à '.$lieu.' Veuillez vous rendre sur '
                                    . 'le site pour plus d\'informations. A très bientôt.'
                                    . 'Le SKICLUB');
                    
        $this->get('mailer')->send($message);
    }
    
}

/*

        $em = $this->getDoctrine()->getManager();
        $saison = new Saison;
        $year = $saison->connaitreSaison();
        $saison = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Saison')->findOneByAnnee($year); 
        $activite = $em->getRepository('SCActiviteBundle:Activite')->find($id);
        $lieu = $em->getRepository('SC\ActiviteBundle\Entity\Lieu')->findOneByNomLieu($nomLieu);
        $user = $this->getDoctrine()->getManager()
                                            ->getRepository('SC\UserBundle\Entity\User')
                                                ->findOneByEmail(array('email' => $request->getSession()->get('email')));        
        
        if (null === $activite) {
           return $this->pageErreur("activite inconnue");
        }
        $newSortie = new Sortie;
        //la sortie qui va etre modifiee
        $sortie = $em->getRepository('SCActiviteBundle:Sortie')->findOneBy(array('dateSortie'=>$dateSortie,'activite'=>$activite,'saison'=>$saison,'lieu'=>$lieu));
        $form = $this->get('form.factory')->create(new SortieType(), $newSortie);
        $form->handleRequest($request);
        
        $newSortie->setActivite($activite);        
        $newSortie->setSaison($saison);        
        $newSortie->setUser($user);
        
        if ($form->isValid()) {
            
            $date = $newSortie->getDateSortie();
            $string = $date->format('Y').'-'.$date->format('m').'-'.$date->format('d').' '.$date->format('H').':'.$date->format('i').':'.$date->format('s');    // si il ya deja une meme date -> erreur                
            if ($this->dateExiste($string,$activite,$saison) == true) {
                    return $this->pageErreur("Une date identique a déjà été validée : deux sorties ne peuvent avoir même date");
                } 
            $newSortie->setDateSortie($string);
            $em->persist($newSortie);
            $listLieu = $em->getRepository('SC\ActiviteBundle\Entity\Lieu')->findAll();

                foreach ($listLieu as $lieu) {
                    if ($lieu->getNomLieu() === $newSortie->getLieu()->getNomLieu()) {
                        $newSortie->setLieu($lieu);
                        $em->remove($sortie);
                        $em->flush();
                        $em->getRepository('SCActiviteBundle:InscriptionSortie')->modifSortie($id,$newSortie->getDateSortie(),$newSortie->getLieu()->getNomLieu(),$year,$dateSortie,$nomLieu);
                        $request->getSession()->getFlashBag()->add('info', 'La sortie a bien été modifiée');
                        return $this->redirect($this->generateUrl('sc_activite_view', array('id' => $sortie->getActivite()->getId())));                
                    }    
                }
            $em->remove($sortie);    
            $em->persist($newSortie->getLieu());
            $em->flush();
            $em->getRepository('SCActiviteBundle:InscriptionSortie')->modifSortie($id,$newSortie->getDateSortie(),$newSortie->getLieu()->getNomLieu(),$year,$dateSortie,$nomLieu);
            $request->getSession()->getFlashBag()->add('info', 'Sortie bien modifiée');
            return $this->redirect($this->generateUrl('sc_activite_view', array('id' => $activite->getId())));
        }

        return $this->render('SCActiviteBundle:Activite:edit.html.twig', array('form'   => $form->createView(),'activite' => $activite,'edit' => 1));// Je passe également l'activité à la vue si jamais elle veut l'afficher))
    }


 */
