<?php

namespace SC\ActiviteBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use SC\ActiviteBundle\Entity\Lieu;
use SC\ActiviteBundle\Form\LieuType;
use SC\ActiviteBundle\Entity\Saison;
use SC\ActiviteBundle\Entity\Activite;
use SC\ActiviteBundle\Entity\Stage;

class StageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('debutStage','date')
            ->add('finStage','date')
            ->add('nomStage')
            ->add('description')
            ->add('prixStage')
            ->add('charges')
            ->add('lieu', new LieuType())
            ->add('saison', new SaisonType())
            ->add('enregistrer','submit');
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SC\ActiviteBundle\Entity\Stage'
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
