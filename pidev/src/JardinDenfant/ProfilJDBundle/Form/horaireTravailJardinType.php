<?php

namespace JardinDenfant\ProfilJDBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class horaireTravailJardinType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('joursemaine',ChoiceType::class,array('choices'=>array('Lundi'=>'Lundi','Mardi'=>'Mardi','Mercredi'=>'Mercredi','Jeudi'=>'jeudi','Vendredi'=>'Vendredi','Samedi'=>'Samedi')))
            ->add('horarireDebutMatin',ChoiceType::class,array('choices'=>array('7h'=>'7h','8h'=>'8h','9h'=>'9h','10h'=>'10h','11h'=>'11h')))
            ->add('horarireFinMatin',array('choices'=>array('8h'=>'8h','9h'=>'9h','10h'=>'10h','11h'=>'11h','12h'=>'12h')))
            ->add('horairemidiDebut',array('choices'=>array('12h'=>'12h','13h'=>'13h','14h'=>'14h','15h'=>'15h','16h'=>'16h')))
            ->add('horairemidiFin',array('choices'=>array('13h'=>'13h','14h'=>'14h','15h'=>'15h','16h'=>'16h','17h'=>'17h')))
            ->add('num_Auth',HiddenType::class)->add('suivant',SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JardinDenfant\ProfilJDBundle\Entity\horaireTravailJardin'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'jardindenfant_profiljdbundle_horairetravailjardin';
    }


}
