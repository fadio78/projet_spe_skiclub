<?php


namespace SC\LicenceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LicenceEditType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
  }

  public function getName()
  {
    return 'sc_licencebundle_licence_edit';
  }

  public function getParent()
  {
    return new LicenceType();
  }
}