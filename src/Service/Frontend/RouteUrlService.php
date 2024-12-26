<?php

namespace App\Service\Frontend;

use App\Entity\Frontend\Article;
use App\Entity\Frontend\ArticleCategory;
use App\Entity\Frontend\City;
use App\Entity\Frontend\Country;
use App\Entity\Frontend\FilterPage;
use App\Entity\Frontend\Page;
use App\Entity\Frontend\Service;
use App\Entity\Frontend\Writer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ManagerException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class RouteUrlService
{
    private string $defaultLocale;

    public function __construct(
        private readonly ParameterBagInterface $container,
        private readonly EntityManagerInterface $em,
        private readonly RouterInterface $router,
    ) {
        $this->defaultLocale = $this->container->get('default_locale');
    }

    public function getUrl($route)
    {
        switch ($route['_route']) {
            case 'service':
                return $this->getService($route);
            case 'writer':
                return $this->getWriter($route);
            case 'category':
                return $this->getCategory($route);
            case 'article':
                return $this->getArticle($route);
            case 'page':
                return $this->getPage($route);
            case 'writers':
            case 'blog':
            case 'home':
            case "order":
                return true;
        }

        return false;
    }

    public function getService($route)
    {
        $serviceRepo = $this->em->getRepository(Service::class);
        return (bool)$serviceRepo->findOneBy(['slug' => $route['slug']]);
    }

    public function getWriter($route)
    {
        $serviceRepo = $this->em->getRepository(Writer::class);
        return (bool)$serviceRepo->findOneBy(['slug' => $route['slug']]);
    }

    public function getCategory($route)
    {
        $serviceRepo = $this->em->getRepository(ArticleCategory::class);
        return (bool)$serviceRepo->findOneBy(['slug' => $route['slug']]);
    }

    public function getArticle($route)
    {
        $serviceRepo = $this->em->getRepository(Article::class);
        return (bool)$serviceRepo->findOneBy(['slug' => $route['slug']]);
    }

    public function getPage($route)
    {
        $serviceRepo = $this->em->getRepository(Page::class);
        return (bool)$serviceRepo->findOneBy(['slug' => $route['slug']]);
    }

    public function delete404Links($content, Request $request)
    {
        $baseUrl = $request->getSchemeAndHttpHost();
        $baseUrlHttps = str_replace('http://', 'https://', $request->getSchemeAndHttpHost());

        if ($content) {
            $crawler = new Crawler($content, null, $baseUrlHttps);
            $crawler->filter('a')->each(
                function (Crawler $node) use ($baseUrl, $baseUrlHttps, $request) {

                    $link = $node->link()->getUri();
                    $linkBaseUrl = 'https://'.parse_url($link, PHP_URL_HOST);

                    if ($linkBaseUrl == $baseUrl || $linkBaseUrl == $baseUrlHttps) {
                        $link = str_replace([$baseUrl, $baseUrlHttps], '', $link);

                        try {
                            $route = $this->router->match($link);
                            $exists = $this->getUrl($route);

                            if (!$exists) {
                                $el = new \DOMElement('t', $node->html());
                                $node->getNode(0)->parentNode->insertBefore($el, $node->getNode(0));
                                $node->getNode(0)->parentNode->removeChild($node->getNode(0));
                            }
                        } catch (ManagerException $exception) {
                        }
                    }
                }
            );

            return $crawler->filter('body')->html();
        } else {
            return $content;
        }
    }

}