<?php


namespace SC\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class NiveauSkiEditType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
  }

  public function getName()
  {
    return 'sc_niveauSkibundle_niveauSki_edit';
  }

  public function getParent()
  {
    return new NiveauSkiType();
  }
}