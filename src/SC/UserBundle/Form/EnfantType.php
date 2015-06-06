<?php

namespace SC\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EnfantType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomEnfant')
            ->add('prenomEnfant')
            ->add('niveauSki', 'entity', array('class'=> 'SCUserBundle:NiveauSki','property' => 'niveau',
          'multiple' => false,'expanded' => false,'required' => true))
            ->add('dateNaissance')
            ->add('ajouter enfant', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SC\UserBundle\Entity\Enfant'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sc_userbundle_enfant';
    }
}
