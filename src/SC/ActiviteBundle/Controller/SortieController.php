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
use SC\ActiviteBundle\Entity\Saison;



class SortieController extends Controller 
{
    //permet d'ajouter une nouvelle sortie
    public function ajoutSortieAction($id,Request $request) {
        
        $sortie = new Sortie();
        $activite = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
        $saison = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Saison')->findOneByAnnee($this->connaitreSaison());
        $user = $this->getDoctrine()->getManager()
                                            ->getRepository('SC\UserBundle\Entity\User')
                                                ->findOneByEmail(array('email' => $request->getSession()->get('email')));
         
        if (is_null($activite)==false) {
            
            $sortie->setActivite($activite);        
            $sortie->setSaison($saison);        
            $sortie->setUser($user);
             
            $form = $this->get('form.factory')->create(new SortieType(), $sortie);
            $form->handleRequest($request);
            
            // On vérifie que les valeurs entrées sont correctes
            if ($form->isValid()) {

                $date = $sortie->getDateSortie();
                $string = $date->format('Y').'-'.$date->format('m').'-'.$date->format('d').' '.$date->format('H').':'.$date->format('i').':'.$date->format('s');
                // si il ya deja une meme date -> erreur
                $this->dateExiste($string); 
                $sortie->setDateSortie($string);
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($saison); 
                $em->flush(); 
                $em->persist($sortie);                
                               
                //si le lieu est n'est pas gere par le manager, on l'ajoute 
                $listLieu = $em->getRepository('SC\ActiviteBundle\Entity\Lieu')->findAll();
                
                    // on ne persist pas lieu il est deja dans la BD
                    foreach ($listLieu as $lieu) {
                        if ($lieu->getNomLieu() === $sortie->getLieu()->getNomLieu()) {
                            $sortie->setLieu($lieu);
                            $em->flush();
                            $request->getSession()->getFlashBag()->add('info', 'La sortie a bien été enregistrée');
                            return $this->redirect($this->generateUrl('sc_activite_view', array('id' => $sortie->getActivite()->getId())));                
                        }    
                    } 
                    
                    $em->persist($sortie->getLieu());
                    $em->flush();
                    return $this->redirect($this->generateUrl('sc_activite_view', array('id' => $sortie->getActivite()->getId())));                
            } 
            return $this->render('SCActiviteBundle:Activite:add.html.twig', array(
                'form' => $form->createView(),
                ));
        }
        else {
            throw new NotFoundHttpException("Cette activité n'existe pas");               
        }
    }
    // teste si la date existe deja dans la BD
    // return true si la date est dans la BD, false sinon
    // $id -> identifiant de l'activite
    // $sortie -> la sortie à la date considerée
    public function dateExiste($date) {
        
        if($this->getDoctrine()->getManager()
                                    ->getRepository('SC\ActiviteBundle\Entity\Sortie')
                                            ->findOneByDateSortie($date) === null) {
            
            return;
            
        }
        else {
             $this->pageErreur("Une date identique a déjà été validée : deux sorties ne peuvent avoir même date");
        }
        
    }
    
    //permet d'afficher toutes les sorties pour l'activite id
    public function voirSortieAction($id) {
     
        $activite = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);   
        
        if (is_null($activite)==false) {
            
            //on recupere toutes les sorties
            $listSortie = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Sortie')->findAll();
            return $this->render('SCActiviteBundle::viewSortie.html.twig',array('listSortie' => $listSortie, 'activite' => $activite ));
        
        }
        else {
            $this->pageErreur("l'activité demandée n'existe pas");
        }
  
    }
    
    //supprime une sortie de l'activite id, la date est passée dans l'url
    public function deleteSortieAction($id, Request $request) {
        
        $em = $this->getDoctrine()->getManager();
        //$idSortie= $request->query->get('idSortie');
        $dateSortie= $request->query->get('date');
        $activite = $em->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
        $sortie = $em->getRepository('SC\ActiviteBundle\Entity\Sortie')
                                                        //->findOneBy(array('idSortie' => $idSortie,'activite' =>  $activite));
                                                        ->findOneBy(array('dateSortie' => $dateSortie,'activite' =>  $activite));
        //au cas ou les paramètres seraient modifiés à la main par quelqu'un
        if (is_null($activite)==false) {
            $this->pageErreur("l'activité demandée n'existe pas");
        }
        if (isset($sortie)==FALSE) {
            $listSortie = $em->getRepository('SC\ActiviteBundle\Entity\Sortie')->findAll();
            return $this->render('SCActiviteBundle::viewSortie.html.twig',array('listSortie' => $listSortie, 'activite' => $activite ));            
        }
        else {
            //envoyer les emails aux utilisateurs inscrits
            $em->remove($sortie);
            $em->flush();
            $listSortie = $em->getRepository('SC\ActiviteBundle\Entity\Sortie')->findAll();
            return $this->render('SCActiviteBundle::viewSortie.html.twig',array('listSortie' => $listSortie, 'activite' => $activite ));
        }
    }
    
    public function pageErreur($message) {
        $response = new Response;
        $response->setContent($erreur);
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
        return $response;
    }
}