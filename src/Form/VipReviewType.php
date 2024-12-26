<?php

namespace App\Form;

use App\Entity\Admin\User;
use App\Entity\Frontend\VipReview;
use App\InputType\PostStatusType;
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

class VipReviewType extends AbstractType
{
    use SeoFieldsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'thumbnail',
                ThumbnailType::class,
                ['label' => 'Upload thumbnail', 'mapped' => false, 'required' => false]
            )
            ->add('title', TextType::class, [
                'label' => 'Page title',
            ])
            ->add('slug', TextType::class, [
                'label' => 'Slug',
                'required' => false
            ])
            ->add('createdAt', DateTimeType::class, [
                'label' => 'Publish date',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'format' => DateTimeType::HTML5_FORMAT,
                'required' => false,
            ])
            ->add('status', PostStatusType::class, ['label' => 'Page status'])
            ->add('author', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('rating', TextType::class, [
                'label' => 'Rating',
                'required' => false,
            ])
            ->add('callToAction', TextareaType::class, [
                'label' => 'Call To Action text',
                'required' => false,
            ])
            ->add('siteUrl', TextType::class, [
                'label' => 'Site URL',
                'required' => false,
            ])
            ->add('exclusivePromos', TextType::class, [
                'label' => 'Exclusive promos',
                'required' => false,
            ])
            ->add('payouts', TextType::class, [
                'label' => 'Payouts',
                'required' => false,
            ])
            ->add('depositLimits', TextType::class, [
                'label' => 'Deposit limits',
                'required' => false,
            ])
            ->add('personalVipManager', TextType::class, [
                'label' => 'Personal Vip Manager',
                'required' => false,
            ])
            ->add('giftAndRewards', TextType::class, [
                'label' => 'Gifts & Rewards',
                'required' => false,
            ])
            ->add('eventsInvitations', TextType::class, [
                'label' => 'Invitations to Events',
                'required' => false,
            ])
            ->add('security', TextType::class, [
                'label' => 'Security & Safety',
                'required' => false,
            ])
            ->add('promoCode', TextType::class, [
                'label' => 'Promo-code',
                'required' => false,
            ])
            ->add('gameVariety', TextType::class, [
                'label' => 'Game Variety',
                'required' => false,
            ])
            ->add('customerSupport', TextType::class, [
                'label' => 'Customer Support',
                'required' => false,
            ])
            ->add('oddsMargin', TextType::class, [
                'label' => 'Odds Margin',
                'required' => false,
            ])
            ->add('wagering', TextType::class, [
                'label' => 'Wagering',
                'required' => false,
            ])

            ->add('withdrawalTime', TextType::class, [
                'label' => 'Withdrawal Time',
                'required' => false,
            ])
            ->add('bonusMaxBet', TextType::class, [
                'label' => 'Bonus Max Bet',
                'required' => false,
            ])
            ->add('paymentLogos', GalleryType::class, [
                'label' => 'Payment Logos',
                'required' => false,
            ])
            ->add('listingText', TextareaType::class, [
                'label' => 'Listing Call To Action',
                'required' => false,
            ])
            ->add('labels', LabelsType::class, [
                'label' => 'Labels',
                'required' => false,
            ])
            ->add('showInSlots', CheckboxType::class, [
                'label' => 'Show in slot machine',
                'required' => false
            ])
          ;

        $this->addSeoFields($builder);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => VipReview::class,
        ]);
    }
}
