<?php


namespace SC\ActiviteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ActiviteEditType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
  }

  public function getName()
  {
    return 'sc_activitebundle_activite_edit';
  }

  public function getParent()
  {
    return new ActiviteType();
  }
}