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
        
        
        
        if(is_null($activite) OR is_null($stage) OR is_null($saison) OR is_null($parents)) {
            $response = new Response;
            $response->setContent("Error 404: not found");
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            return $response;
        }
        
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
            
            $inscriptionStage = new InscriptionStage();
            $inscriptionStage -> setActivite($activite);
            $inscriptionStage -> setUser($parents);
            $inscriptionStage -> setDebutStage($debutStage);
            $inscriptionStage -> setFinStage($finStage);
            $inscriptionStage -> setPrixPayeStage(0);
            $inscriptionStage ->setNomEnfant($enfant -> getNomEnfant());
            $inscriptionStage ->setPrenomEnfant($enfant -> getPrenomEnfant());
            $er = $em ->getRepository('SC\ActiviteBundle\Entity\InscriptionStage');
            if ($this->estInscrit($activite, $stage, $parents, $enfant->
                    getNomEnfant(),$enfant->getPrenomEnfant()) === false)
            {
                $em->persist($inscriptionStage);
                $em->flush();
                $request->getSession()->getFlashBag()->add('info', 'Inscription bien enregistrée');
                return $this->redirect($this->generateUrl('sc_activite_homepage'));
            } else {
                $request->getSession()->getFlashBag()->add('info', 'Enfant déjà inscrit');
                return $this->render('SCActiviteBundle:Stage:viewEnfant.html.twig', array(
            'form' => $form->createView()));
            }
        } else {
            return $this->render('SCActiviteBundle:Stage:viewEnfant.html.twig',
                    array('form' => $form->createView()));
        }
    }
    
    //retourne true si l'enfant est deja inscrit au stage
    //false sinon
    public function estInscrit($activite, $stage, $userParent, $nomEnfant, $prenomEnfant) {
        $em = $this->getDoctrine()->getManager();
        $enfantInscrit = $em->getRepository('SC\ActiviteBundle\Entity\InscriptionStage')
                                ->findOneBy(array('debutStage'=>$stage->getDebutStage(),'finStage'=>$stage->getFinStage(),'activite'=>$activite, 
                                        'user'=>$userParent,'nomEnfant' => $nomEnfant, 'prenomEnfant'=> $prenomEnfant));
        if($enfantInscrit == null) {
            return false;
        }
        else {
            return true;
        }
    }
    
    public function viewChildrenStagesaction($id, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $season = new Saison;
        $year = $season->connaitreSaison();
        $saison = $em->getRepository('SC\ActiviteBundle\Entity\Saison')->find($year);
        $activite = new Activite();
        $activite = $em->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
        
        
        $listeInscriptionStages = $em->getRepository('SC\ActiviteBundle\Entity\InscriptionStage')->findBy(array('activite'=>$activite));
        if (is_null($activite)) {
            $response = new Response;
            $response->setContent("Error 404: not found");
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            return $response;
        } else {
            return $this->render('SCActiviteBundle:Stage:viewAll.html.twig',
                    array('listeInscriptionStages' => $listeInscriptionStages, 'activite' => $activite));
        }
    }
    
}