<?php

namespace JardinDenfant\ProfilJDBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class adresseJardinType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('coderue',NumberType::class)
            ->add('nomrue')
            ->add('municipalite')
            ->add('gouvernorat',array('choices'=>array('Ariana'=>'Ariana','Béja'=>'Béja','Ben Arous'=>'Ben Arous','Bizerte'=>'Bizerte','Gabès'=>'Gabès','Gafsa'=>'Gafsa','Jendouba'=>'Jendouba','Kairouan'=>'Kairouan','Kasserine'=>'Kasserine','Kébili'=>'Kébili','Le Kef'=>'Le Kef','Mahdia'=>'Mahdia','La Manouba'=>'La Manouba','Médenine'=>'Médenine','Monastir'=>'Monastir','Nabeul'=>'Nabeul','Sfax'=>'Sfax','Sidi Bouzid'=>'Sidi Bouzid','Siliana'=>'Siliana','Sousse'=>'Sousse','Zaghouan'=>'Zaghouan','Tataouine'=>'Tataouine','Tozeur'=>'Tozeur','Tunis'=>'Tunis')))->add('cin',HiddenType::class)->add('Valider',SubmitType::class);

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JardinDenfant\ProfilJDBundle\Entity\adresseJardin'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'jardindenfant_profiljdbundle_adressejardin';
    }


}
