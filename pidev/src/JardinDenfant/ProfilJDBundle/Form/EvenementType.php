<?php

namespace JardinDenfant\ProfilJDBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nonE')
            ->add('apropos')

            ->add('start')
            ->add('heure')
            ->add('duree')
            ->add('adresse')
            ->add('nbrPlaceMax')
            ->add('numauth',HiddenType::class)
           ->add('image', FileType::class, array('label' => 'Image(JPG)','data_class'=>null))
            ->add('Ajouter',SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JardinDenfant\ProfilJDBundle\Entity\Evenement'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'jardindenfant_profiljdbundle_evenement';
    }


}
