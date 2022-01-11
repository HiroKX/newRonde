<?php

namespace App\Form;

use App\Entity\Archive;
use App\Entity\Article;
use App\Entity\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre de l\'article',
            ])
            ->add('utitre', TextType::class, [
                'label' => 'Sous-titre de l\'article',
            ])
            ->add('contenu', TextareaType::class, [
                'label' => 'Contenu de l\'article',
                'attr' => [
                    'class' => 'js-editor',
                ],
                'required' => false,
            ])
            ->add('attachments', FileType::class, [
                'required' => false,
                'multiple' => true,
                'label' => 'Fichier(s)',
                'mapped' => false,
            ])
            ->add('images',FileType::class,[
                'required' => false,
                'multiple' => true,
                'label' => 'Image(s)',
                'attr'=>['accept' => "image/*"],
                'mapped' => false,
            ])
            ->add('type', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un type d\'article...',
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('annee', EntityType::class, [
                'class' => Archive::class,
                'choice_label' => 'denom',
                'placeholder' => 'Choisir une année...',
                'multiple' => false,
                'expanded' => false,
            ]);
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
