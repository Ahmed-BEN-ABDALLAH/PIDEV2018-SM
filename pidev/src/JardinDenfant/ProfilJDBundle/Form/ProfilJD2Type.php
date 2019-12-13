<?php

namespace JardinDenfant\ProfilJDBundle\Form;

use blackknight467\StarRatingBundle\Form\RatingType;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilJD2Type extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('numauth',TextareaType::class,['attr' => ['readonly' => true],])
            ->add('nomJD',TextareaType::class,['attr' => ['readonly' => true],])
            ->add('adresse',TextareaType::class,['attr' => ['readonly' => true],])
            //->add('description', TextareaType::class)
            ->add('num',TextareaType::class,['attr' => ['readonly' => true],])
           // ->add('image', FileType::class, array('label' => 'Image(JPG)','data_class'=>null))

            ->add('id',HiddenType::class)

            ->add('rating', RatingType::class, [
                'label' => 'Rating'
            ])->add('description',TextareaType::class,['attr' => ['readonly' => true],])


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
