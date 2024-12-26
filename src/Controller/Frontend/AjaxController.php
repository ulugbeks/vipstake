<?php

namespace App\Controller\Frontend;

use App\Bot\TelegramBot;
use App\Entity\Frontend\ArticleReview;
use App\Entity\Frontend\Banner;
use App\Entity\Frontend\City;
use App\Entity\Frontend\ContactMessage;
use App\Entity\Frontend\ModelMessage;
use App\Repository\Frontend\ArticleRepository;
use App\Repository\Frontend\CityRepository;
use App\Repository\Frontend\ModelRepository;
use App\Repository\Frontend\ModelReviewRepository;
use App\Repository\Frontend\VipReviewRepository;
use App\Repository\Frontend\WriterRepository;
use App\Repository\Frontend\WriterReviewRepository;
use App\Service\Frontend\BinomService;
use App\Service\Frontend\LeadService;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Translation\LocaleSwitcher;

#[Route('/ajax', name: 'ajax_')]
class AjaxController extends FrontendController
{
    #[Route('/reviews', name: 'reviews')]
    public function getReviews(Request $request, VipReviewRepository $vipReviewRepository)
    {
        if ($request->isXmlHttpRequest()) {
            $html = '';
            $page = $request->query->get('page');
            $index = $request->query->get('index');

            $reviews = Pagerfanta::createForCurrentPageWithMaxPerPage(
                new QueryAdapter($vipReviewRepository->findAllByRatingQueryBuilder()),
                $page,
                ReviewController::REVIEWS_PER_PAGE
            );

            foreach ($reviews as $review) {
                $html .= $this->renderView('frontend/review/listing/review-card.html.twig', [
                    'review' => $review,
                    'index' => ++$index
                ]);
            }

            return  $this->json([
                'nextPage' => $reviews->hasNextPage(),
                'content' => $html
            ]);
        }

        return $this->createNotFoundException();
    }
}