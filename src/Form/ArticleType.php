<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre',TextType::class,['label'=>"Titre de l'article"])
            ->add('utitre',TextType::class,['label'=>"Sous-titre de l'article "])
            ->add('contenu',TextType::class,['label'=>"Contenu de l'article "])
            ->add('attachments', FileType::class,['required'   => false,'multiple' => true,'label'=>'Fichier(s)','mapped'=>false])
            ->add('type')
            ->add('annee')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
