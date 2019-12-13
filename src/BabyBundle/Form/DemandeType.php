<?php

namespace BabyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
class DemandeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'nom du produit',
                    'class'=>'form-control border-color-1'
                )))
            ->add('prenom', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Prenom',
                    'class'=>'form-control border-color-2'
                )))
            ->add('adrese', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Adresse',
                    'class'=>'form-control border-color-3'
                )))
//            ->add('disponibilite', DateType::class, array(
//                'attr' => array(
//                    'placeholder' => 'Disponibilite',
//                    'class'=>'form-control border-color-1'
//                )))
            ->add('email', TextType::class, array(
                'attr' => array(

                    'class'=>'form-control border-color-4'
                )))
            ->add('numtel', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Numero Telephone',
                    'class'=>'form-control border-color-1'
                )))
            ->add('prix', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Prix',
                    'class'=>'form-control border-color-5'
                )))
            ->add('description', TextareaType::class, array(
                'attr' => array(
                    'placeholder' => 'Description',
                    'class'=>'form-control border-color-6'
                )))
            ->add('image', FileType::class,array('data_class' => null))
            ->add('Ajouter',SubmitType::class, array(
                'attr' => array(

                    'class'=>'btn btn-primary'
                )));

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BabyBundle\Entity\Demande'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'babybundle_demande';
    }


}
