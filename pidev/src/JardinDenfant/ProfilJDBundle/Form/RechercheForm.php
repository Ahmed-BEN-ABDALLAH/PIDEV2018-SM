<?php
/**
 * Created by PhpStorm.
 * User: Hella Boukari
 * Date: 19/02/2018
 * Time: 23:53
 */

namespace JardinDenfant\ProfilJDBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercheForm  extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('adresse');


    }
    public function configureOptions(OptionsResolver $resolver)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'jardindenfant_profiljdbundle_profiljd';
    }
}