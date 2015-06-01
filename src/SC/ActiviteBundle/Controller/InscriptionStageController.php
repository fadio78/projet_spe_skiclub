<?php

namespace SC\ActiviteBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use SC\ActiviteBundle\Entity\Activite;
use SC\ActiviteBundle\Form\ActiviteType;
use SC\ActiviteBundle\Form\LieuType;
use SC\ActiviteBundle\Form\StageType;
use SC\ActiviteBundle\Form\ActiviteEditType;
use SC\UserBundle\Entity\User;
use SC\UserBundle\Entity\EnfantRepository;
use SC\UserBundle\Entity\Licence;
use SC\ActiviteBundle\Entity\Stage;
use SC\ActiviteBundle\Entity\Lieu;
use SC\ActiviteBundle\Entity\Saison;
use SC\ActiviteBundle\Entity\InscriptionStage;
use SC\ActiviteBundle\Form\InscriptionStageType;


class InscriptionStageController extends Controller 
{
    //genere une page d'erreur si besoin
    public function pageErreur($message) {
        $response = new Response;
        $response->setContent($message);
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
        return $response;
    }
    
    //liste les enfants d'un utilisateur
    public function viewChildrenAction($id, $debutStage, $finStage, Request $request) {

        $em = $this->getDoctrine()->getManager();
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('SCActiviteBundle:Stage');
        
        $season = new Saison();
        $year = $season->connaitreSaison();
        $activite = $em->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
        $saison = $em->getRepository('SC\ActiviteBundle\Entity\Saison')->findOneByAnnee($year);
        $stage = $em->getRepository('SC\ActiviteBundle\Entity\Stage')
            ->findOneBy(array('debutStage' => $debutStage, 'finStage' => $finStage,'activite' =>  $activite));
        $parents = $em->getRepository('SC\UserBundle\Entity\User')->findOneByEmail($request->getSession()->get('email'));
        $email = $parents->getEmail();
        
        $inscriptionStage = new InscriptionStage();
        $inscriptionStage -> setActivite($activite);
        $inscriptionStage -> setEmail($email);
        $inscriptionStage -> setDebutStage($debutStage);
        $inscriptionStage -> setFinStage($finStage);
        $inscriptionStage -> setPrixPayeStage(0);
        
        if(is_null($activite) OR is_null($stage) OR is_null($saison) OR is_null($parents)) {
            $response = new Response;
            $response->setContent("Error 404: not found");
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            return $response;
        }
        
        $listeEnfants = $em->getRepository('SC\UserBundle\Entity\Enfant')->findBy(array('userParent' => $parents));
        $defaultData = array('message' => 'Type your message here');
        
        $form = $this->createFormBuilder($defaultData)
           ->add('Enfant', 'entity', array('class'=> 'SC\UserBundle\Entity\Enfant'
               ,'property' => 'prenomNom', 'multiple' => false,'expanded' => false,
               'required' => true, 'query_builder' => function (EnfantRepository $repository) 
               use ($email) { return $repository->getEnfant($email); }))
          ->add('Enregistrer','submit')->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
        $data = $form->getData();
        $enfant = $data ['Enfant'];
        $inscriptionStage ->setNomEnfant($enfant -> getNomEnfant());
        $inscriptionStage ->setPrenomEnfant($enfant -> getPrenomEnfant());
        return $this->render('SCActiviteBundle:Stage:viewEnfant.html.twig', array('listeEnfants'=> $listeEnfants,'activite'=> $activite));
        } else {
            $response = new Response;
            $response->setContent("Error 404: not found");
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            return $response;
        }
    }
    
    // met a jout les tables  inscriptionStage
    public function inscrireAction($id, $userParent, $nomEnfant, $prenomEnfant, Request $request) {
        $em = $this->getDoctrine()->getManager();
        //verifier qu'il a la licence
        $activite = $em->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
        $stage = $request->getSession()->get('stage');
        if ($this->estInscrit($id,$stage, $userParent, $nomEnfant, $prenomEnfant)==true) {
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
        return $this->render('SCActiviteBundle:Activite:view.html.twig', array('activite'=> $activite));
    }
    
    //retourne true si l'enfant est deja inscrit au stage
    //false sinon
    public function estInscrit($id, $stage, $userParent, $nomEnfant, $prenomEnfant) {
        $em = $this->getDoctrine()->getManager();
        $enfantInscrit = $em->getRepository('SC\ActiviteBundle\Entity\InscriptionStage')
                                ->findOneBy(array('debutStage'=>$stage->getDebutStage(),'finStage','idActivite'=>$id, 
                                        'emailParent'=>$userParent,'nomEnfant' => $nomEnfant, 'prenomEnfant'=> $prenomEnfant));
        if($enfantInscrit == null) {
            return false;
        }
        else {
            return true;
        }
    }
}    