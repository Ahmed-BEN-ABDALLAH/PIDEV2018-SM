<?php

namespace Sante\SpecialisteBundle\Form;

use blackknight467\StarRatingBundle\Form\RatingType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class MedecinSpecialisteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('cin')->add('nom')->add('prenom')->add('description', TextareaType::class)->add('dateNaissance',\Symfony\Component\Form\Extension\Core\Type\DateType::class)->add('specialite',ChoiceType::class,array('choices'=>array('Pédiatre'=>'Pédiatre','Orthophoniste'=>'Orthophoniste')))->add('id',HiddenType::class)->add('telephone',NumberType::class)->add('numCabinet',NumberType::class)->add('mail',EmailType::class)->add('lat',NumberType::class)->add('lng',NumberType::class)->add('gouvernorat',ChoiceType::class,
            array('choices'=>array('Ariana'=>'Ariana','Béja'=>'Béja','Ben Arous'=>'Ben Arous','Bizerte'=>'Bizerte','Gabès'=>'Gabès','Gafsa'=>'Gafsa','Jendouba'=>'Jendouba','Kairouan'=>'Kairouan','Kasserine'=>'Kasserine','Kébili'=>'Kébili','Le Kef'=>'Le Kef','Mahdia'=>'Mahdia','La Manouba'=>'La Manouba','Médenine'=>'Médenine','Monastir'=>'Monastir','Nabeul'=>'Nabeul','Sfax'=>'Sfax','Sidi Bouzid'=>'Sidi Bouzid','Siliana'=>'Siliana','Sousse'=>'Sousse','Zaghouan'=>'Zaghouan','Tataouine'=>'Tataouine','Tozeur'=>'Tozeur','Tunis'=>'Tunis')))->add('municipalite')->add('rating',HiddenType::class)->add('image', FileType::class, array('label' => 'Image(JPG)'))->add('justif', FileType::class, array('label' => 'Pièce justificatif en PDF'))->add('etatverif',HiddenType::class)->add('Valider',SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sante\SpecialisteBundle\Entity\MedecinSpecialiste'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sante_specialistebundle_medecinspecialiste';
    }


}
