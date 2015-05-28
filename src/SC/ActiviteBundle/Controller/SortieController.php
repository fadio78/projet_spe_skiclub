<?php

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


class SortieController extends Controller 
{
    
    public function ajoutSortieAction($id,Request $request) {
        
        $sortie = new Sortie();
        $activite = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
        
        if (is_null($activite)==false) {
            
            $sortie = $sortie->setActivite($activite);        
            $form = $this->get('form.factory')->create(new SortieType(), $sortie);
            $form->handleRequest($request);
            
            // On vérifie que les valeurs entrées sont correctes
            if ($form->isValid()) {
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($sortie);                
                //si le lieu est n'est pas gere par le manager, on l'ajoute a la base
                $listLieu = $em->getRepository('SC\ActiviteBundle\Entity\Lieu')->findAll();
                
                foreach ($listLieu as $lieu) {
                    if ($lieu->getNomLieu() === $sortie->getLieu()->getNomLieu()) {
                        $sortie->setLieu($lieu);
                        // on ne persist pas lieu il est deja dans la BD
                        $em->flush();
                        $listSortie = $em->getRepository('SC\ActiviteBundle\Entity\Sortie')->findAll();
                        return $this->redirect($this->generateUrl('sc_activite_view', array('id' => $sortie->getActivite()->getId(), 'listSortie' => $listSortie)));                
                    }    
                } 
                
                $em->persist($sortie->getLieu());
                $em->flush();
                $listSortie = $em->getRepository('SC\ActiviteBundle\Entity\Sortie')->findAll();
                return $this->redirect($this->generateUrl('sc_activite_view', array('id' => $sortie->getActivite()->getId(), 'listSortie' => $listSortie)));
            }
                return $this->render('SCActiviteBundle::add.html.twig', array(
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
    
    public function voirSortieAction($id) {
     
        $activite = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);   
        
        if (is_null($activite)==false) {
            
            //on recupere toutes les sorties
            $listSortie = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Sortie')->findAll();
            return $this->render('SCActiviteBundle::viewSortie.html.twig',array('listSortie' => $listSortie, 'activite' => $activite ));
        
        }
        else {
            throw new NotFoundHttpException("L'activité d'id ".$id." n'existe pas.");
        }
  
    }
}