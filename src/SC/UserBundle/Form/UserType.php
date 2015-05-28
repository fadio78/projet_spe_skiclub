<?php

namespace SC\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('nom')
            ->add('prenom')
            ->add('telephone')
            ->add('adresse')
            ->add('commune')
            ->add('password','repeated',
                array(
                    'type' => 'password',
                    'invalid_message' => 'Password fields do not match',
                    'first_options' => array('label' => 'Password'),
                    'second_options' => array('label' => 'Repeat Password')
                )
            )
            //->add('type')
            //->add('isActive')
            //->add('salt')
             ->add('enregistrer', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SC\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sc_userbundle_user';
    }
}
