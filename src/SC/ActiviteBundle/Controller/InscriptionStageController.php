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
use SC\LicenceBundle\Entity\Licence;
use SC\LicenceBundle\Entity\LicenceEnfant;
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
    
    //permet de voir la liste des enfants, de choisir l'enfant à inscrire et de l'inscrire
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
        
        // On crée le formulaire pour faire la liste déroulante des enfants de l'utilisateur
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
            
            $licence = new Licence();
            $licence = $em->getRepository('SC\LicenceBundle\Entity\Licence')->find($activite->getLicence());
            if ($this->haveLicence($licence, $saison, $enfant->getNomEnfant()
                    ,$enfant->getPrenomEnfant(),$parents)==false) {
            $request->getSession()->getFlashBag()->add('info', $enfant->getPrenomEnfant().'  ne possède pas la licence pour cette activité');
                return $this->render('SCActiviteBundle:Stage:viewEnfant.html.twig', array(
            'form' => $form->createView()));
            }
            
            
            
            $inscriptionStage = new InscriptionStage();
            $inscriptionStage -> setActivite($activite);
            $inscriptionStage -> setUser($parents);
            $inscriptionStage -> setDebutStage($debutStage);
            $inscriptionStage -> setFinStage($finStage);
            $inscriptionStage -> setSaison($saison);
            $inscriptionStage -> setPrixPayeStage(0);
            $inscriptionStage -> setNomEnfant($enfant -> getNomEnfant());
            $inscriptionStage -> setPrenomEnfant($enfant -> getPrenomEnfant());
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
    
    // Voir la liste des inscriptions aux stages de tous les enfants pour une saison
    public function viewChildrenStagesAction($id, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $season = new Saison;
        $year = $season->connaitreSaison();
        $saison = $em->getRepository('SC\ActiviteBundle\Entity\Saison')->find($year);
        $activite = new Activite();
        $activite = $em->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
        
        $listeInscriptionStages = $em->getRepository('SC\ActiviteBundle\Entity\InscriptionStage')->findBy(array('activite'=>$activite, 'saison'=>$saison));
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

    //retourne true si l'enfant possède la licence pour la saison courante
    //false sinon
    public function haveLicence($licence,$saison,$nomEnfant,$prenomEnfant,$emailParent) {
        $em = $this->getDoctrine()->getManager();
        //on regarde si l'enfant possède la licence pour l'activite pour la saison donnee
        $enfant = $em->getRepository('SC\UserBundle\Entity\LicenceEnfant')
                        ->findOneBy(array('licence' => $licence, 'saison' => $saison,
                            'email' => $emailParent, 'nomEnfant'=>$nomEnfant,'prenomEnfant'=>$prenomEnfant));
        if ($enfant == null) {
            return false;
        }
        else {
            return true;
        }
    }
    
    public function confirmPaymentAction($id, $email, $nomEnfant, $prenomEnfant, $debutStage, $finStage, Request $request) {
        $em = $this->getDoctrine()->getManager();
        
        $season = new Saison;
        $year = $season->connaitreSaison();
        $saison = $em->getRepository('SC\ActiviteBundle\Entity\Saison')->find($year);
        
        $user = new User();
        $user = $em->getRepository('SC\UserBundle\Entity\User')->find($email);
        
        $activite = new Activite();
        $activite = $em->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
        
        $stage = $em->getRepository('SC\ActiviteBundle\Entity\Stage')->findOneBy(
                array('activite'=>$activite,'debutStage'=>$debutStage,
                    'finStage'=>$finStage));
        $prixTotal = $stage->getPrixStage() + $stage->getCharges();
        $em->getRepository('SC\ActiviteBundle\Entity\InscriptionStage')
                                ->validationPayment($prixTotal, $activite, $user, $nomEnfant, $prenomEnfant, $debutStage, $finStage);
        
        $listeInscriptionStages = $em->getRepository('SC\ActiviteBundle\Entity\InscriptionStage')
                ->findBy(array('saison'=>$saison, 'user'=>$user));
        
        $request->getSession()->getFlashBag()->add('info', 'Paiement confirmé');
        return $this->render('SCActiviteBundle:Stage:viewAllStagesUser.html.twig', array('listeInscriptionStages'=>$listeInscriptionStages));
    }
    
    public function deleteInscriptionStageAction($id, $email, $nomEnfant, $prenomEnfant, $debutStage, $finStage, Request $request) {
        $em = $this->getDoctrine()->getManager();
        
        $season = new Saison;
        $year = $season->connaitreSaison();
        $saison = $em->getRepository('SC\ActiviteBundle\Entity\Saison')->find($year);
        
        $user = new User();
        $user = $em->getRepository('SC\UserBundle\Entity\User')->find($email);
        
        $activite = new Activite();
        $activite = $em->getRepository('SC\ActiviteBundle\Entity\Activite')->find($id);
        
        $inscriptionStage = $em->getRepository('SC\ActiviteBundle\Entity\InscriptionStage')->findOneBy(
                array('activite'=>$activite, 'user'=>$user, 'nomEnfant'=>$nomEnfant,'prenomEnfant'=>$prenomEnfant,'debutStage'=>$debutStage,
                    'finStage'=>$finStage));
        
        $em->remove($inscriptionStage);
        $em->flush();
        
        $listeInscriptionStages = $em->getRepository('SC\ActiviteBundle\Entity\InscriptionStage')
                ->findBy(array('saison'=>$saison, 'user'=>$user));
        
        $request->getSession()->getFlashBag()->add('info', 'Inscription supprimée');
        return $this->render('SCActiviteBundle:Stage:viewAllStagesUser.html.twig', array('listeInscriptionStages'=>$listeInscriptionStages));
        
        
    }
}