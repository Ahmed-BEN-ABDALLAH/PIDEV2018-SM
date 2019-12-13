<?php

namespace Sante\SpecialisteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class horaireTravailType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('joursemaine',HiddenType::class)->add('horarireDebutMatin',ChoiceType::class,array('choices'=>array('--'=>'--','7h'=>'7h','8h'=>'8h','9h'=>'9h','10h'=>'10h','11h'=>'11h')))->add('horarireFinMatin',ChoiceType::class,array('choices'=>array('--'=>'--','8h'=>'8h','9h'=>'9h','10h'=>'10h','11h'=>'11h','12h'=>'12h')))->add('horairemidiDebut',ChoiceType::class,array('choices'=>array('--'=>'--','12h'=>'12h','13h'=>'13h','14h'=>'14h','15h'=>'15h','16h'=>'16h')))->add('horairemidiFin',ChoiceType::class,array('choices'=>array('--'=>'--','13h'=>'13h','14h'=>'14h','15h'=>'15h','16h'=>'16h','17h'=>'17h','18h'=>'18h','19h'=>'19h')))->add('id',HiddenType::class)->add('idhoraire',HiddenType::class)->add('Valider',SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sante\SpecialisteBundle\Entity\horaireTravail'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sante_specialistebundle_horairetravail';
    }


}
