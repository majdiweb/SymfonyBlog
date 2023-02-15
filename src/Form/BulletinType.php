<?php

namespace App\Form;

use App\Entity\Bulletin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BulletinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label'=> 'Titre du Bulletin'
            ] )
            ->add('category', ChoiceType::class, [
                'label'=> 'Catégorie',
                'choices' => [
                    'Général' => 'general',
                    'Divers' => 'divers',
                    'Urgent' => 'urgent',
                ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('content', TextareaType::class, [
                'label'=>'Contenu',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr'=> [
                    'style'=> 'margin-top:5px',
                    'class'=> 'btn btn-success',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bulletin::class,
        ]);
    }
}
