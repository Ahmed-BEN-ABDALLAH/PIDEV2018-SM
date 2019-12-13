<?php

namespace JardinDenfant\ProfilJDBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilJDType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('numauth')
            ->add('nomJD')
            ->add('adresse')
            ->add('description', TextareaType::class)
            ->add('num')
            ->add('longitude')

            ->add('latitude')
            ->add('id',HiddenType::class)
            ->add('image', FileType::class, array('label' => 'Image(JPG)'))
            ->add('ok',SubmitType::class);

    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JardinDenfant\ProfilJDBundle\Entity\ProfilJD'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'jardindenfant_profiljdbundle_profiljd';
    }


}
