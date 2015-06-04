<?php

namespace SC\ActiviteBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use SC\LicenceBundle\Form\LicenceType;
use SC\LicenceBundle\Entity\Licence;

class ActiviteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomActivite')
            ->add('description')
            ->add('prixActivite') 
            ->add('licence', 'entity', array('class'=> 'SCLicenceBundle:Licence','property' => 'typeLicence',
          'multiple' => false,'expanded' => false,'required' => false))
            ->add('enregistrer','submit');
    }
    
    /**
     * 
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SC\ActiviteBundle\Entity\Activite'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sc_activitebundle_activite';
    }
}
