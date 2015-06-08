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
use SC\ActiviteBundle\Form\SortieEditType;



class SortieController extends Controller 
{
    //permet d'ajouter une nouvelle sortie
    public function ajoutSortieAction($id,Request $request) {
        
        if ($request->getSession()->get('email') == null) {
            return $this->pageErreur("Vous devez être connecté pour accèder à ce lien");
        }        
        
        $year = $request->getSession()->get('year');
        $sortie = new Sortie();
        $activite = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
        $saison = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Saison')->findOneByAnnee($year);
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
                if ($this->dateExiste($string,$activite,$saison) == true) {
                    return $this->pageErreur("Une date identique a déjà été validée : deux sorties ne peuvent avoir même date");
                }
                
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
                    $request->getSession()->getFlashBag()->add('info', 'La sortie a bien été enregistrée');
                    return $this->redirect($this->generateUrl('sc_activite_view', array('id' => $sortie->getActivite()->getId())));                
            } 
            return $this->render('SCActiviteBundle:Activite:add.html.twig', array(
                'form' => $form->createView(),
                'vueLicence'=>1));
        }
        else {
            return $this->pageErreur("l'activité demandée n'existe pas");               
        }
    }
    
    // teste si la date existe deja dans la BD
    // return true si la date est dans la BD, false sinon
    public function dateExiste($date,$activite,$saison) {
        
        if($this->getDoctrine()->getManager()
                                    ->getRepository('SC\ActiviteBundle\Entity\Sortie')
                                            ->findOneBy(array('dateSortie'=>$date,'activite'=> $activite,'saison' => $saison)) === null) {
            
            return false;
            
        }
        else {
            return true;
        }
        
    }
    
    //permet d'afficher toutes les sorties pour l'activite id sur la saison en cours-> PLUS UTILISEE
    public function voirSortieAction($id) {
                   
        $saison = new Saison;
        $year = $saison->connaitreSaison();
        $saison = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Saison')->findOneByAnnee($year);     
        $activite = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);   
        
        if (is_null($activite)==false) {
            
            //on recupere toutes les sorties pour la saison en cours et l'activite donnee
            $listSortie = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Sortie')->findBy(array('activite'=>$activite,'saison'=>$saison));
            return $this->render('SCActiviteBundle:Sortie:viewSortie.html.twig',array('listSortie' => $listSortie, 'activite' => $activite ));
        
        }
        else {
            return $this->pageErreur("l'activité demandée n'existe pas");
        }
  
    }
    
    //supprime une sortie de l'activite id, la date est passée dans l'url
    public function deleteSortieAction($id, Request $request, $dateSortie) {
        
        
        if ($request->getSession()->get('email') == null) {
            return $this->pageErreur("Vous devez être connecté pour accèder à ce lien");
        }
        
        $em = $this->getDoctrine()->getManager();
        $saison = new Saison;
        $year = $saison->connaitreSaison();
        $saison = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Saison')->findOneByAnnee($year);
        $activite = $em->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
        $sortie = $em->getRepository('SC\ActiviteBundle\Entity\Sortie')
                                                        ->findOneBy(array('dateSortie' => $dateSortie,'activite' =>  $activite /*,'dateSortie'=>$dateSortie,'lieu'=>$nomLieu*/,'saison'=>$saison));
        //au cas ou les paramètres seraient modifiés à la main par quelqu'un
        
        if (is_null($activite)==true) {
            return $this->pageErreur("l'activité demandée n'existe pas ou le lieu n'est pas reference");
        }
        if (isset($sortie)==FALSE) {
            $listSortie = $em->getRepository('SC\ActiviteBundle\Entity\Sortie')->findBy(array('activite'=>$activite,'saison'=>$saison));
            return $this->render('SCActiviteBundle:Sortie:viewSortie.html.twig',array('listSortie' => $listSortie, 'activite' => $activite ));            
        }
        else {
            
            $this->estInscrit($sortie->getDateSortie(),$id,$sortie->getLieu()->getNomLieu());
            $em->remove($sortie);
            $em->flush();
            $request->getSession()->getFlashBag()->add('info', 'La sortie a bien été supprimée, et un mail a été envoyé aux personnes inscrites');
            $listSortie = $em->getRepository('SC\ActiviteBundle\Entity\Sortie')->findBy(array('activite'=>$activite,'saison'=>$saison));
            return $this->render('SCActiviteBundle:Sortie:viewSortie.html.twig',array('listSortie' => $listSortie, 'activite' => $activite ));
        }
    }
    // propose des choix a l'utilisateur
    // essentiellement pour la vue
    public function actionSortieAction($id, Request $request, $dateSortie, $lieu) {
        
        if ($request->getSession()->get('email') == null) {
            return $this->pageErreur("Vous devez être connecté pour accèder à ce lien");
        }
        
        $em = $this->getDoctrine()->getManager();
        $saison = new Saison;
        $year = $saison->connaitreSaison();
        $saison = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Saison')->findOneByAnnee($year);
        $activite = $em->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
        
            if (is_null($activite)==true) {
                return $this->pageErreur("l'activité demandée n'existe pas");
            }

        $listSortie = $em->getRepository('SC\ActiviteBundle\Entity\Sortie')->findBy(array('activite'=>$activite,'saison'=>$saison));
        return $this->render('SCActiviteBundle:Sortie:viewSortie.html.twig',array('listSortie' => $listSortie, 'activite' => $activite, 'dateSortie'=>$dateSortie,'lieu'=>$lieu ));
    }
    
    
    // permet de retourner une page d'erreur
    public function pageErreur($message) {
        $response = new Response;
        $response->setContent($message);
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
        return $response;
    }
    
    //supprime les enfants inscrit a une sortie donnee pour une saison donnee
    //envoie un mail pour prevenir que la sortie a ete annulee
    public function estInscrit($dateSortie,$id,$lieu) {
        $em = $this->getDoctrine()->getManager();
        $saison = new Saison;
        $year = $saison->connaitreSaison();
        $inscrits = $em->getRepository('SC\ActiviteBundle\Entity\InscriptionSortie')
                            ->findBy(array('dateSortie'=>$dateSortie,'idActivite'=>$id,'saison'=> $year));
        
        foreach ($inscrits as $enfant) {
            $this->envoieMail($enfant->getEmailParent(), $dateSortie, $lieu);
            $em->remove($enfant);
        }
        $em->flush();
    }
  
    //envoie un mail a l'adresse 'email' pour prevenir les personnes inscrites 
    public function envoieMail($email,$dateSortie,$lieu) {
            
        $message = \Swift_Message::newInstance()                     
                            ->setSubject('SKICLUB: Sortie annulee')
                            ->setFrom('sfr@hotmail.com')
                            ->setTo($email)
                            ->setBody('Bonjour, Nous avons le regret de vous annoncer que la sortie prévue le '.$dateSortie.' a : '.$lieu.' est annulee. Pardon pour la gene occasionnee. Le SKICLUB');
                    
        $this->get('mailer')->send($message);
        
        
    }
    
    public function editSortieAction($id, Request $request,$dateSortie)
    {
        if ($request->getSession()->get('email') == null) {
            return $this->pageErreur("Vous devez être connecté pour accèder à ce lien");
        }
     
        $em = $this->getDoctrine()->getManager();
        $saison = new Saison;
        $year = $saison->connaitreSaison();
        $saison = $this->getDoctrine()->getManager()->getRepository('SC\ActiviteBundle\Entity\Saison')->findOneByAnnee($year); 
        $activite = $em->getRepository('SCActiviteBundle:Activite')->find($id);
        $user = $this->getDoctrine()->getManager()
                                            ->getRepository('SC\UserBundle\Entity\User')
                                                ->findOneByEmail(array('email' => $request->getSession()->get('email')));        
        
        if (null === $activite) {
           return $this->pageErreur("activite inconnue");
        }
        $newSortie = new Sortie;
        //la sortie qui va etre modifiee
        $sortie = $em->getRepository('SCActiviteBundle:Sortie')->findOneBy(array('dateSortie'=>$dateSortie,'activite'=>$activite,'saison'=>$saison/*,'lieu'=>$lieu*/));
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
                        $em->getRepository('SCActiviteBundle:InscriptionSortie')->modifSortie($id,$newSortie->getDateSortie()/*,$newSortie->getLieu()->getNomLieu()*/,$year,$dateSortie/*,$nomLieu*/);
                        $request->getSession()->getFlashBag()->add('info', 'La sortie a bien été modifiée');
                        return $this->redirect($this->generateUrl('sc_activite_view', array('id' => $sortie->getActivite()->getId())));                
                    }    
                }
            $em->remove($sortie);    
            $em->persist($newSortie->getLieu());
            $em->flush();
            $em->getRepository('SCActiviteBundle:InscriptionSortie')->modifSortie($id,$newSortie->getDateSortie()/*,$newSortie->getLieu()->getNomLieu()*/,$year,$dateSortie/*,$nomLieu*/);
            $request->getSession()->getFlashBag()->add('info', 'Sortie bien modifiée');
            return $this->redirect($this->generateUrl('sc_activite_view', array('id' => $activite->getId())));
        }

        return $this->render('SCActiviteBundle:Activite:edit.html.twig', array('form'   => $form->createView(),'activite' => $activite,'edit' => 1));// Je passe également l'activité à la vue si jamais elle veut l'afficher))
    }
   
}
    