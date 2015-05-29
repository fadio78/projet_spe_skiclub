<?php

namespace SC\ActiviteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use SC\ActiviteBundle\Entity\Lieu;
use SC\ActiviteBundle\Form\LieuType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;

class SortieType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add($builder->create('dateSortie','datetime')->addViewTransformer(new DateTimeToStringTransformer())) 
            ->add('dateSortie') 
            ->add('lieu', new LieuType())
            ->add('enregistrer','submit');
                   
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SC\ActiviteBundle\Entity\Sortie',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sc_activitebundle_sortie';
    }
}
