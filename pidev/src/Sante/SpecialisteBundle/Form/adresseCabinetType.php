<?php

namespace Sante\SpecialisteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class adresseCabinetType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('id',HiddenType::class)->add('coderue',NumberType::class)->add('nomrue')->add('gouvernorat',ChoiceType::class,
            array('choices'=>array('Ariana'=>'Ariana','Béja'=>'Béja','Ben Arous'=>'Ben Arous','Bizerte'=>'Bizerte','Gabès'=>'Gabès','Gafsa'=>'Gafsa','Jendouba'=>'Jendouba','Kairouan'=>'Kairouan','Kasserine'=>'Kasserine','Kébili'=>'Kébili','Le Kef'=>'Le Kef','Mahdia'=>'Mahdia','La Manouba'=>'La Manouba','Médenine'=>'Médenine','Monastir'=>'Monastir','Nabeul'=>'Nabeul','Sfax'=>'Sfax','Sidi Bouzid'=>'Sidi Bouzid','Siliana'=>'Siliana','Sousse'=>'Sousse','Zaghouan'=>'Zaghouan','Tataouine'=>'Tataouine','Tozeur'=>'Tozeur','Tunis'=>'Tunis')))->add('idadresse',HiddenType::class)->add('municipalite')->add('Valider',SubmitType::class);

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sante\SpecialisteBundle\Entity\adresseCabinet'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sante_specialistebundle_adressecabinet';
    }


}
