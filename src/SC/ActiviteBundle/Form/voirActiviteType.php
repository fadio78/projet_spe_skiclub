<?php

namespace SC\ActiviteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use SC\ActiviteBundle\Entity\Activite;


class voirActiviteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder
            ->add('activite', 'entity', array('class'=> 'SCActiviteBundle:Activite','property' => 'nomActivite',
          'multiple' => false,'expanded' => false,'required' => false));   
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SC\ActiviteBundle\Entity\Activite',
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
