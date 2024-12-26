<?php

namespace App\Controller\Frontend;

use App\Entity\Frontend\Article;
use App\Entity\Frontend\ArticleCategory;
use App\Entity\Frontend\City;
use App\Entity\Frontend\Country;
use App\Entity\Frontend\FilterPage;
use App\Entity\Frontend\Model;
use App\Entity\Frontend\Page;
use App\Entity\Frontend\Service;
use App\Entity\Frontend\Writer;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SiteMapController extends FrontendController
{
    #[Route('/sitemap.xml', name: 'frontend_sitemap', priority: 3)]
    public function index()
    {
        $sitemaps = [
            $this->generateUrl('frontend_sitemap_page', [], UrlGeneratorInterface::ABSOLUTE_URL,),
            $this->generateUrl('frontend_sitemap_service', [], UrlGeneratorInterface::ABSOLUTE_URL),
            $this->generateUrl('frontend_sitemap_writers', [], UrlGeneratorInterface::ABSOLUTE_URL),
            $this->generateUrl('frontend_sitemap_categories', [], UrlGeneratorInterface::ABSOLUTE_URL),
            $this->generateUrl('frontend_sitemap_articles', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ];


        array_walk_recursive($sitemaps, function (&$value) {
            $value = str_replace('http://', 'https://', $value);
        });


        $response = new Response(
            $this->renderView('snippets/sitemap_index.html.twig', ['sitemaps' => $sitemaps]),
            200
        );
        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }

    #[Route('/page_sitemap.xml', name: 'frontend_sitemap_page', priority: 3)]
    public function page()
    {
        $urls = [];
        $mainPage = $this->getOption('home_page');
        $pages = $this->em->getRepository(Page::class)->findAllIndexable($mainPage);
        $mainPage = $this->em->getRepository(Page::class)->find($mainPage);

        $urls[] = $this->createUrl($mainPage, 'home', 1, []);

        /** @var Page $item */
        foreach ($pages as $item) {
            $urls[] = $this->createUrl($item, 'page', 1, [
               'slug' => $item->getSlug()
            ]);
        }

        return $this->renderMap($urls);
    }

    #[Route('/service_sitemap.xml', name: 'frontend_sitemap_service', priority: 3)]
    public function service()
    {
        $urls = [];
        $services = $this->em->getRepository(Service::class)->findAllIndexable();

        /** @var Service $item */
        foreach ($services as $item) {
            $urls[] = $this->createUrl($item, 'service', 1, [
                'slug' => $item->getSlug()
            ]);
        }

        return $this->renderMap($urls);
    }

    #[Route('/writers_sitemap.xml', name: 'frontend_sitemap_writers', priority: 3)]
    public function writers()
    {
        $urls = [];
        $services = $this->em->getRepository(Writer::class)->findAll();

        $urls [] = [
            'loc' => str_replace(
                'http://',
                'https://',
                $this->generateUrl(
                    'writers',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            ),
            'changefreq' => 'Monthly',
            'priority' => 0.5
        ];

        /** @var Writer $item */
        foreach ($services as $item) {
            $urls[] = $this->createUrl($item, 'writer', 1, [
                'slug' => $item->getSlug()
            ]);
        }

        return $this->renderMap($urls);
    }

    #[Route('/categories_sitemap.xml', name: 'frontend_sitemap_categories', priority: 3)]
    public function categories()
    {
        $urls = [];
        $categories = $this->em->getRepository(ArticleCategory::class)->findAll();

        /** @var ArticleCategory $item */
        foreach ($categories as $item) {
            $urls[] = $this->createUrl($item, 'category', 1, [
                'slug' => $item->getSlug()
            ]);
        }

        return $this->renderMap($urls);
    }

    #[Route('/articles_sitemap.xml', name: 'frontend_sitemap_articles', priority: 3)]
    public function articles()
    {
        $urls = [];
        $categories = $this->em->getRepository(Article::class)->findAll();

        $urls [] = [
            'loc' => str_replace(
                'http://',
                'https://',
                $this->generateUrl(
                    'blog',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            ),
            'changefreq' => 'Monthly',
            'priority' => 0.5
        ];

        /** @var Article $item */
        foreach ($categories as $item) {
            $urls[] = $this->createUrl($item, 'article', 1, [
                'slug' => $item->getSlug(),
                'category' => $item->getCategory()->getSlug()
            ]);
        }

        return $this->renderMap($urls);
    }

    private function createUrl($item, $route, $priority, $routeParams)
    {
        return [
            'loc' => str_replace(
                'http://',
                'https://',
                $this->generateUrl(
                    $route,
                    $routeParams,
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            ),
            'lastmod' => $item->getUpdatedAt()?->format('Y-m-d'),
            'changefreq' => 'Daily',
            'priority' => $priority,
        ];
    }

    private function renderMap($urls)
    {
        $response = new Response(
            $this->renderView('snippets/sitemap.html.twig', ['urls' => $urls]),
            200
        );
        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }
}