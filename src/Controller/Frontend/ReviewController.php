<?php

namespace App\Controller\Frontend;

use App\Controller\Admin\VipReviewController;
use App\Entity\Frontend\VipReview;
use App\PageBuilder\Service\PbRenderService;
use App\Repository\Frontend\VipReviewRepository;
use App\Service\Admin\SettingsService;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reviews', name: 'frontend_review_')]
class ReviewController extends FrontendController
{
    public const REVIEWS_PER_PAGE = 1;
    #[Route('/', name: 'listing', priority: 255)]
    public function listing(VipReviewRepository $vipReviewRepository, SettingsService $service)
    {
        $fields = $service->getArrayByArea(VipReviewController::OPTIONS_AREA);

        return $this->render('frontend/review/listing.html.twig', [
            'fields' => $fields,
            'reviews' => Pagerfanta::createForCurrentPageWithMaxPerPage(
                new QueryAdapter($vipReviewRepository->findAllByRatingQueryBuilder()),
                1,
                self::REVIEWS_PER_PAGE
            ),
        ]);
    }

    #[Route('/{slug}', name: 'item')]
    public function item(VipReview $review, PbRenderService $renderService)
    {
        $content = $renderService->render($review->getContent());

        return $this->render('frontend/review/review.html.twig', [
            'review' => $review,
            'content' => $content,
        ]);
    }
}