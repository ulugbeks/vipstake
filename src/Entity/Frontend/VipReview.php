<?php

namespace App\Entity\Frontend;

use App\Attribute\Admin\Autotranslate;
use App\Entity\Admin\User;
use App\Interface\FieldsGroupInterface;
use App\Interface\NavInterface;
use App\Interface\StatusInterface;
use App\Repository\Frontend\PageRepository;
use App\Repository\Frontend\VipReviewRepository;
use App\Trait\Frontend\PageMetaTrait;
use App\Trait\Frontend\SeoMetaTrait;
use App\Trait\Frontend\SeoTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Contract\Entity\BlameableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\SoftDeletableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Blameable\BlameableTrait;
use Knp\DoctrineBehaviors\Model\SoftDeletable\SoftDeletableTrait;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

#[ORM\Entity(repositoryClass: VipReviewRepository::class)]
class VipReview implements BlameableInterface,
    StatusInterface, FieldsGroupInterface, NavInterface
{
    use BlameableTrait;
    use SoftDeletableTrait;
    use SeoMetaTrait;
    use PageMetaTrait;
    use SeoTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'vipReviews')]
    private ?User $author = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $thumbnail = null;

    #[ORM\Column(length: 255)]
    #[Autotranslate]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Autotranslate]
    private ?string $content = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Gedmo\Slug(fields: ['title'], updatable: true, separator: '-', unique: false)]
    #[Autotranslate]
    private ?string $slug = null;

    #[ORM\Column(nullable: true)]
    private ?float $rating = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $callToAction = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $siteUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $exclusivePromos = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $payouts = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $depositLimits = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $personalVipManager = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $giftAndRewards = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $eventsInvitations = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $security = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $promoCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $gameVariety = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customerSupport = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $oddsMargin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $wagering = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $withdrawalTime = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $bonusMaxBet = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $paymentLogos = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $listingText = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $labels = null;

    #[ORM\Column(nullable: true)]
    private ?bool $showInSlots = null;


    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string|null $slug
     */
    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }


    public function getNavLabel(): string
    {
       return $this->getTitle();
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getCallToAction(): ?string
    {
        return $this->callToAction;
    }

    public function setCallToAction(?string $callToAction): static
    {
        $this->callToAction = $callToAction;

        return $this;
    }

    public function getSiteUrl(): ?string
    {
        return $this->siteUrl;
    }

    public function setSiteUrl(?string $siteUrl): static
    {
        $this->siteUrl = $siteUrl;

        return $this;
    }

    public function getExclusivePromos(): ?string
    {
        return $this->exclusivePromos;
    }

    public function setExclusivePromos(?string $exclusivePromos): static
    {
        $this->exclusivePromos = $exclusivePromos;

        return $this;
    }

    public function getPayouts(): ?string
    {
        return $this->payouts;
    }

    public function setPayouts(?string $payouts): static
    {
        $this->payouts = $payouts;

        return $this;
    }

    public function getDepositLimits(): ?string
    {
        return $this->depositLimits;
    }

    public function setDepositLimits(?string $depositLimits): static
    {
        $this->depositLimits = $depositLimits;

        return $this;
    }

    public function isPersonalVipManager(): ?string
    {
        return $this->personalVipManager;
    }

    public function setPersonalVipManager(?string $personalVipManager): static
    {
        $this->personalVipManager = $personalVipManager;

        return $this;
    }

    public function getGiftAndRewards(): ?string
    {
        return $this->giftAndRewards;
    }

    public function setGiftAndRewards(?string $giftAndRewards): static
    {
        $this->giftAndRewards = $giftAndRewards;

        return $this;
    }

    public function getEventsInvitations(): ?string
    {
        return $this->eventsInvitations;
    }

    public function setEventsInvitations(?string $eventsInvitations): static
    {
        $this->eventsInvitations = $eventsInvitations;

        return $this;
    }

    public function getSecurity(): ?string
    {
        return $this->security;
    }

    public function setSecurity(?string $security): static
    {
        $this->security = $security;

        return $this;
    }

    public function getPromoCode(): ?string
    {
        return $this->promoCode;
    }

    public function setPromoCode(?string $promoCode): static
    {
        $this->promoCode = $promoCode;

        return $this;
    }

    public function getGameVariety(): ?string
    {
        return $this->gameVariety;
    }

    public function setGameVariety(?string $gameVariety): static
    {
        $this->gameVariety = $gameVariety;

        return $this;
    }

    public function getCustomerSupport(): ?string
    {
        return $this->customerSupport;
    }

    public function setCustomerSupport(?string $customerSupport): static
    {
        $this->customerSupport = $customerSupport;

        return $this;
    }

    public function getOddsMargin(): ?string
    {
        return $this->oddsMargin;
    }

    public function setOddsMargin(?string $oddsMargin): static
    {
        $this->oddsMargin = $oddsMargin;

        return $this;
    }

    public function getWagering(): ?string
    {
        return $this->wagering;
    }

    public function setWagering(?string $wagering): static
    {
        $this->wagering = $wagering;

        return $this;
    }

    public function getWithdrawalTime(): ?string
    {
        return $this->withdrawalTime;
    }

    public function setWithdrawalTime(?string $withdrawalTime): static
    {
        $this->withdrawalTime = $withdrawalTime;

        return $this;
    }

    public function getBonusMaxBet(): ?string
    {
        return $this->bonusMaxBet;
    }

    public function setBonusMaxBet(?string $bonusMaxBet): static
    {
        $this->bonusMaxBet = $bonusMaxBet;

        return $this;
    }

    public function getPaymentLogos(): ?string
    {
        return $this->paymentLogos;
    }

    public function setPaymentLogos(?string $paymentLogos): static
    {
        $this->paymentLogos = $paymentLogos;

        return $this;
    }

    public function getListingText(): ?string
    {
        return $this->listingText;
    }

    public function setListingText(?string $listingText): static
    {
        $this->listingText = $listingText;

        return $this;
    }

    public function getLabels(): ?string
    {
        return $this->labels;
    }

    public function setLabels(?string $labels): static
    {
        $this->labels = $labels;

        return $this;
    }

    public function isShowInSlots(): ?bool
    {
        return $this->showInSlots;
    }

    public function setShowInSlots(?bool $showInSlots): static
    {
        $this->showInSlots = $showInSlots;

        return $this;
    }
}
