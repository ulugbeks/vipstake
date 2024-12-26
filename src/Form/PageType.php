<?php

namespace App\Form;

use App\Entity\Admin\User;
use App\Entity\Frontend\Page;
use App\InputType\PostStatusType;
use App\InputType\TemplateType;
use App\InputType\ThumbnailType;
use App\Trait\Admin\SeoFieldsTrait;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
{
    use SeoFieldsTrait;
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Page title',
            ])
            ->add('slug', TextType::class, [
                'label' => 'Slug',
                'required' => false
            ])
            ->add('content', EditorType::class, [
                'label' => 'Page content',
                'required' => false,
            ])
            ->add(
                'thumbnail',
                ThumbnailType::class,
                ['label' => 'Upload thumbnail', 'mapped' => false, 'required' => false]
            )
            ->add('status', PostStatusType::class, ['label' => 'Page status'])
            ->add('author', EntityType::class, [
                'label' => 'Author',
                'class' => User::class,
                'choice_label' => 'name',
            ])
            ->add('createdAt', DateTimeType::class, [
                'label' => 'Publish date',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'format' => DateTimeType::HTML5_FORMAT,
                'required' => false,
            ])
            ->add('template', TemplateType::class, ['label' => 'Template']);

        $this->addSeoFields($builder);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
        ]);
    }
}
