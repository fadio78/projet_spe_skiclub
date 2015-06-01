<?php

namespace SC\ActiviteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use SC\ActiviteBundle\Entity\Sortie;
use SC\ActiviteBundle\Form\SortieType;
use SC\UserBundle\Entity\EnfantRepository;
use SC\ActiviteBundle\Entity\SortieRepository;

class InscriptionSortieType extends AbstractType
{
    public $parents;
    public $actvitie;
    
    public function __construct($user,$act) {
        $this->parents = $user;
        $this->actvitie = $act;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   $user = $this->parents;
        $act = $this->actvitie;
        
        $builder
            ->add('sortie', 'entity', array('class'=> 'SCActiviteBundle:Sortie','multiple' => false,'expanded' => false,'required' => false,
                'query_builder'=> function(SortieRepository $repo) use($act){
                return $repo->getPublishedQueryBuilder($act); }))
            ->add('emailParent', 'entity', array('class'=> 'SCUserBundle:Enfant','multiple' => false,'expanded' => false,'required' => false, 
                'query_builder'=> function(EnfantRepository $repo) use($user){
                return $repo->getPublishedQueryBuilder($user); }))
            ->add('inscription','submit');        
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SC\ActiviteBundle\Entity\InscriptionSortie',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sc_activitebundle_inscriptionsortie';
    }
}
