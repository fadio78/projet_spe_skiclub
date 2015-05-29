<?php
namespace SC\ActiviteBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use SC\ActiviteBundle\Entity\Stage;
use SC\ActiviteBundle\Entity\Activite;
use SC\ActiviteBundle\Form\StageType;
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
    
    
    public function addAction($id,Request $request) {
        
        $stage = new Stage();
        $activite = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
        
        if (is_null($activite)==false) {
            
            $stage = $stage->setActivite($activite);        
            $form = $this->get('form.factory')->create(new StageType(), $stage);
            $form->handleRequest($request);
            
            // On vérifie que les valeurs entrées sont correctes
            if ($form->isValid()) {
                
                $em = $this->getDoctrine()->getManager();
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
                
                if (booleanLieu == false) {
                    // on persist lieu car il n'est pas dans la base
                    $em->persist($stage->getLieu());
                }
                if (booleanSaison == false) {
                    // on persist saison car il n'est pas dans la base
                    $em->persist($stage->getSaison());
                }
                
                $em->flush();
                $listeStages = $em->getRepository('SC\ActiviteBundle\Entity\Stage')->findAll();
                return $this->redirect($this->generateUrl('sc_activite_viewsStage', 
                        array('id' => $stage->getActivite()->getId(), 'listeStages' => $listeStages)));
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
    
    public function viewAction($id) {
     
        $activite = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);   
        
        if (is_null($activite)==false) {
            
            //on recupere tous les stages
            $listeStages = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Stage')->findAll();
            return $this->render('SCActiviteBundle:Stage:view.html.twig',array('listeStages' => $listeStages, 'activite' => $activite ));
        
        }
        else {
            throw new NotFoundHttpException("L'activité d'id ".$id." n'existe pas.");
        }
  
    }
    
}
