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
use SC\ActiviteBundle\Entity\InscriptionSortie;
use SC\ActiviteBundle\Form\InscriptionSortieType;
use Symfony\Component\HttpFoundation\Cookie;

class InscriptionSortieController extends Controller 
{
    //genere une page d'erreur si besoin
    public function pageErreur($message) {
        $response = new Response;
        $response->setContent($message);
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
        return $response;
    }
    
    //liste les enfants d'un utilisateur
    public function getEnfantAction($id,Request $request,$dateSortie,$lieu) {
           
        $em = $this->getDoctrine()->getManager();
        $season = new Saison;
        $year = $season->connaitreSaison();
        $activite = $em->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
        $saison = $em->getRepository('SC\ActiviteBundle\Entity\Saison')->findOneByAnnee($year);
        $nomLieu = $em->getRepository('SC\ActiviteBundle\Entity\Lieu')->findOneByNomLieu($lieu);
        $sortie = $em->getRepository('SC\ActiviteBundle\Entity\Sortie')
                                                        ->findOneBy(array('dateSortie' => $dateSortie,'activite' =>  $activite, 
                                                                                            'saison'=>$saison,'lieu'=>$nomLieu));
        $parents = $em->getRepository('SC\UserBundle\Entity\User')->findOneByEmail($request->getSession()->get('email'));
        
        if(is_null($activite) OR is_null($sortie) OR is_null($nomLieu)) {
            return $this->pageErreur("paramètres entrés invalides");
        }
        $request->getSession()->set('sortie', $sortie);
        $enfants = $em->getRepository('SC\UserBundle\Entity\Enfant')->findBy(array('userParent' => $parents));
        
        return $this->render('SCActiviteBundle:Sortie:viewEnfant.html.twig', array('enfants'=> $enfants,'activite'=> $activite));        
        
      /*  if (is_null($activite)==true) {
            return $this->pageErreur("l'activite demandée n'existe pas !");
        }
        
        $inscription = new InscriptionSortie;
        //on recupere le userParent
        $email = $request->getSession()->get('email'); 
        $parents = $this->getDoctrine()->getManager()->getRepository('SC\UserBundle\Entity\User')->findOneByEmail($email);
        $form = $this->get('form.factory')->create(new InscriptionSortieType($parents,$activite), $inscription);
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            //on teste si l'enfant est deja inscrit
            $sortie = $inscription->getSortie();
            $enfant = $inscription->getEmailParent();

            //mise a jour des differents champs de l'instance d'InscriptionObjet
            $inscription->setIdActivite($activite);
            $inscription->setNomEnfant($enfant->getNomEnfant());
            $inscription->setPrenomEnfant($enfant->getPrenomEnfant());
            $inscription->setParticipation(0);

            //requete sur inscriptionsorite impossible ??
            //$listParticipant = $em->getRepository('SC\ActiviteBundle\Entity\InscriptionSortie')->findBy(array('sortie'=>$sortie,'emailParent'=>$enfant));
            $listParticipant = $em->getRepository('SC\ActiviteBundle\Entity\InscriptionSortie')->findAll();
            
            $em->persist($inscription);
            $em->flush();
            
            $request->getSession()->set('inscription', $inscription);
            return $this->render('SCActiviteBundle::viewEnfant.html.twig', array('enfants'=> $enfant, 'sortie' => $sortie));
        }
        return $this->render('SCActiviteBundle::viewSortie.html.twig', array(
            'form' => $form->createView(), 'inscription' => 1,
            ));*/
    }
    
    // met a jout les table  inscriptionSortie
    public function inscrireEnfantAction($id,Request $request,$userParent,$nomEnfant,$prenomEnfant) {
        $em = $this->getDoctrine()->getManager();
        //verifier qu'il a la licence
        
        $activite = $em->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
        $sortie = $request->getSession()->get('sortie');
        if ($this->estInscrit($id,$sortie, $userParent, $nomEnfant, $prenomEnfant)==true) {
            return $this->pageErreur($nomEnfant.' '.'est déja inscrit à cette sortie');
        }
        
        $inscriptionSortie = new InscriptionSortie;
        $inscriptionSortie->setDateSortie($sortie->getDateSortie());
        $inscriptionSortie->setEmailParent($userParent);
        $inscriptionSortie->setIdActivite($id);
        $inscriptionSortie->setNomEnfant($nomEnfant);
        $inscriptionSortie->setPreNomEnfant($prenomEnfant);
        $inscriptionSortie->setParticipation(0);
        
        //on persist
        $em->persist($inscriptionSortie);
        $em->flush();
        $mesInscriptions = $em->getRepository('SC\ActiviteBundle\Entity\InscriptionSortie')->findBy(array('emailParent'=>$userParent));
        $request->getSession()->set('mesInscriptions', $mesInscriptions);
        return $this->render('SCActiviteBundle:Activite:view.html.twig', array('activite'=> $activite));
    }
    
    //retourne true si l'enfant est deja inscrit a la sortie
    //false sinon
    public function estInscrit($id,$sortie,$userParent,$nomEnfant,$prenomEnfant) {
        $em = $this->getDoctrine()->getManager();
        $listeInscrit = $em->getRepository('SC\ActiviteBundle\Entity\InscriptionSortie')
                                ->findOneBy(array('dateSortie'=>$sortie->getDateSortie(),'idActivite'=>$id, 
                                        'emailParent'=>$userParent,'nomEnfant' => $nomEnfant, 'prenomEnfant'=> $prenomEnfant));
        if($listeInscrit == null) {
            return false;
        }
        else {
            return true;
        }
    }
}    