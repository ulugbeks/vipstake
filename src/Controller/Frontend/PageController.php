<?php

namespace App\Controller\Frontend;

use App\Entity\Frontend\City;
use App\Entity\Frontend\Country;
use App\Entity\Frontend\Model;
use App\Entity\Frontend\Page;
use App\Entity\Frontend\SearchOffer;
use App\PageBuilder\Service\PageBuilderService;
use App\Repository\Frontend\ModelRepository;
use App\Repository\Frontend\PageRepository;
use App\Service\Frontend\ContentParserService;
use App\Service\Frontend\RouteUrlService;
use App\Service\Frontend\TableOfContentService;
use Doctrine\ORM\Exception\ManagerException;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\HttpKernel\EventListener\AbstractSessionListener;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class PageController extends FrontendController
{
    #[Route('/', name: 'home')]
//    #[Cache(public: true, maxage: 86400, mustRevalidate: true)]
    public function home(
        PageRepository $repository,
        PageBuilderService $pageBuilder
    ): Response {
        $homePageId = $this->getOption('home_page');
        $page = $repository->find($homePageId);

        $response = $this->render($page->getTemplate(), [
            'fields' => $this->getFields($page),
            'page' => $page,
        ]);

        $response->headers->set(AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER, true);

        return $response;
    }

    #[Route('/{slug}/', name: 'page', defaults: ['_locale' => '%default_locale%'])]
//    #[Cache(public: true, maxage: 86400, mustRevalidate: true)]
    public function show(
        Page $page,
        Breadcrumbs $breadcrumbs,
    ): Response {
        $breadcrumbs->addItem('Home', $this->generateUrl('home'));
        $breadcrumbs->addItem($page->getTitle());
        $homePageId = $this->getOption('home_page');

        if ($homePageId == $page->getId()) {
            throw $this->createNotFoundException();
        }

        $response = $this->render($page->getTemplate() ?? 'frontend/page/page.html.twig', [
            'page' => $page,
            'fields' => $this->getFields($page),
        ]);

//        $response->headers->set(AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER, true);

        return $response;
    }
}
