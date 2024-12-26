<?php

namespace App\Service\Admin;

use App\Entity\Frontend\Article;
use App\Entity\Frontend\ArticleCategory;
use App\Entity\Frontend\City;
use App\Entity\Frontend\Country;
use App\Entity\Frontend\NavItem;
use App\Entity\Frontend\Page;
use App\Entity\Frontend\Service;
use App\Entity\Frontend\Writer;
use App\Service\Frontend\RouteUrlService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Asset\Context\RequestStackContext;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class NavItemService
{
    private $urlGenerator;
    private $em;
    private string $defaultLocale;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        EntityManagerInterface $em,
        private readonly RouteUrlService $urlService,
        private readonly RouterInterface $router,
        private readonly ParameterBagInterface $container,
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->em = $em;
        $this->defaultLocale = $this->container->get('default_locale');
    }

    private static int $index = 0;

    public static function getEntityTypes()
    {
        return [
            'Page' => [
                'class' => Page::class,
                'route' => 'page_default',
                'param' => 'slug',
            ],
            'Service' => [
                'class' => Service::class,
                'route' => 'country_default',
                'param' => 'slug',
            ],
            'Articles' => [
                'class' => Article::class,
                'route' => 'city_default',
                'param' => ['slug', 'category.slug'],
            ],
            'Categories' => [
                'class' => ArticleCategory::class,
                'route' => 'city_default',
                'param' => 'slug',
            ],
            'Writers' => [
                'class' => Writer::class,
                'route' => 'city_default',
                'param' => 'slug',
            ],
        ];
    }

    public static function setIndexes($navItems)
    {
        $navItems = $navItems->filter(function (NavItem $item) {
            return $item->getParentItem() == null;
        });

        $result = [];
        /** @var NavItem $navItem */
        foreach ($navItems as $navItem) {
            $item = self::setIndex($navItem);
            $result[] = $item;
        }

        return $result;
    }

    private static function setIndex($item)
    {
        /** @var NavItem $item */
        $item->index = self::$index++;

        foreach ($item->getNavItems() as $child) {
            self::setIndex($child);
        }

        return $item;
    }

    public function generateRoute(NavItem $item, Request $request, $strategy = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        $locale = $request->getLocale();
        $entityClass = self::getEntityTypes()[$item->getEntityType()]['class'] ?? "Custom";

        switch ($entityClass) {
            case Page::class:
                /** @var Page $page */
                $page = $this->em->getRepository($entityClass)->find($item->getEntityId());

                return $this->urlGenerator->generate(
                    'page',
                    ['slug' => $page->getSlug()],
                    $strategy
                );
            case Service::class:
                /** @var Service $page */
                $page = $this->em->getRepository($entityClass)->find($item->getEntityId());

                return $this->urlGenerator->generate(
                    'service',
                    ['slug' => $page->getSlug()],
                    $strategy
                );
            case Article::class:
                /** @var Article $page */
                $page = $this->em->getRepository($entityClass)->find($item->getEntityId());

                return $this->urlGenerator->generate(
                    'article',
                    ['slug' => $page->getSlug(), 'category' => $page->getCategory()->getSlug()],
                    $strategy
                );
            case ArticleCategory::class:
                /** @var ArticleCategory $page */
                $page = $this->em->getRepository($entityClass)->find($item->getEntityId());

                return $this->urlGenerator->generate(
                    'category',
                    ['slug' => $page->getSlug()],
                    $strategy
                );
            case Writer::class:
                /** @var Writer $page */
                $page = $this->em->getRepository($entityClass)->find($item->getEntityId());

                return $this->urlGenerator->generate(
                    'writer',
                    ['slug' => $page->getSlug()],
                    $strategy
                );
            default:
                $baseUrl = $request->getSchemeAndHttpHost();
                $baseUrlHttps = str_replace('http://', 'https://', $request->getSchemeAndHttpHost());
                $link = $item->getUrl();
                $link = str_replace([$baseUrl, $baseUrlHttps], '', $link);

                try {
                    $route = $this->router->match($link);
                    return $link;

//                    return $this->urlService->getUrl($route, $request->getLocale());
                } catch (\Exception $exception) {
                    return $item->getUrl();
                }
        }

    }
}