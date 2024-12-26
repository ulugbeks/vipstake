<?php

namespace App\Twig;

use App\Entity\Admin\Meta;
use App\Entity\Admin\Option;
use App\Entity\Frontend\NavMenu;
use App\Entity\Frontend\SeoTemplates;
use App\Entity\Frontend\Service;
use App\Helpers\Helper;
use App\Service\Frontend\ContentParserService;
use App\Service\Frontend\RouteUrlService;
use App\Service\Frontend\SchemaService;
use App\Service\Frontend\TableOfContentService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ManagerException;
use Doctrine\ORM\PersistentCollection;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Part\File;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Extension\RuntimeExtensionInterface;

class FrontRuntime implements RuntimeExtensionInterface
{
    private string $defaultLocale;

    public function __construct(
        private readonly Environment $twig,
        private readonly ParameterBagInterface $container,
        private readonly EntityManagerInterface $em,
        private readonly TranslatorInterface $translator,
        private readonly ContentParserService $contentParser,
        private readonly SchemaService $schemaService,
        private readonly RouterInterface $router,
        private readonly RouteUrlService $urlService,
    ) {
        $this->defaultLocale = $container->get('default_locale');
    }

    public function renderImage($assetUrl, $classes = null, $isLazy = false)
    {
        if ($assetUrl) {
            $webp = '';
            $acceptableExtensions = ['jpeg', 'jpg', 'png'];

            $file = new File($assetUrl);
            $extension = pathinfo($file->getFilename(), PATHINFO_EXTENSION);

            if (in_array($extension, $acceptableExtensions, true)) {
                $webp = str_replace($extension, 'webp', $assetUrl);
            }

            return $this->twig->render('snippets/picture.html.twig', [
                'url' => $assetUrl,
                'webp' => $webp,
                'classes' => $classes,
                'lazy' => $isLazy,
            ]);
        }

        return null;
    }


    public function uploadedAsset($asset)
    {
        $projectDir = $this->container->get('kernel.project_dir');

        return '/uploads/media/'.$asset;
    }

    public function ucwordsFilter(string $string): string
    {
        return ucwords($string);
    }

    public function getReadingTime($content)
    {
        $wordsCount = str_word_count($content);

        return round($wordsCount / 200);
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function getOption($slug, $translate = false): mixed
    {
        if ($translate) {
            return $this->translator->trans(
                $this->em->getRepository(Option::class)->findOneBy(['slug' => $slug])?->getSlug(),
                [],
                'options'
            );
        }

        return $this->em->getRepository(Option::class)->findOneBy(['slug' => $slug])?->getValue();
    }

    public function getSocials()
    {
        $result = [];
        $socials = $this->em->getRepository(Option::class)->getSocials();

        /** @var Option $social */
        foreach ($socials as $social) {
            $result[$social->getSlug()] = $social->getValue();
        }

        return $result;
    }


    public function shuffle($array)
    {
        shuffle($array);

        return $array;
    }

    public function getWordCount(string $string)
    {
        return str_word_count($string);
    }

    public function getSeoMeta($entity)
    {
        $class = get_class($entity);

        return $this->em->getRepository(SeoTemplates::class)->findOneBy(['entity' => $class]);
    }

    public function getNav(string $name)
    {
        return $this->em->getRepository(NavMenu::class)->findOneBy(['name' => $name]);
    }

    public function html_decode($string)
    {
        return strip_tags(htmlspecialchars_decode(html_entity_decode($string)));
    }

    public function getField($entity, $id, $label)
    {
        $metaRepository = $this->em->getRepository(Meta::class);
        $meta = $metaRepository->findField($entity, $id, $label);

        if (!$meta) {
            return null;
        }

        return json_decode($meta->getContent(), true);
    }


    public function generateFaqScheme($faq)
    {
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "FAQPage",
            "mainEntity" => [

            ],
        ];

        foreach ($faq as $item) {
            $schema['mainEntity'][] = [
                "@type" => "Question",
                "name" => $item['question'] ?? null,
                "acceptedAnswer" => [
                    "@type" => "Answer",
                    "text" => $item['answer'] ?? null,
                ],
            ];
        }

        return json_encode($schema);
    }

    public function filename($url)
    {
        $pathInfo = pathinfo($url);

        return $pathInfo['basename'];
    }

    public function intVal($string)
    {
        return intval($string);
    }

    public function renderBlocks($string)
    {
        $blocks = json_decode($string, true)['blocks'] ?? [];

        return $this->contentParser->renderHTML($blocks);
    }

    public function toc($string)
    {
        return Urlizer::urlize($string);
    }

    public function getTOC($string)
    {
        return (new TableOfContentService($this->renderBlocks($string)))->create()->getLinks();
    }

    public function getPercentage($num, $from)
    {
        return Helper::calculatePercentage($num, $from);
    }

    public function toHttps($url)
    {
        return str_replace('http://', 'https://', $url);
    }

    public function getNavSchema(NavMenu $menu, Request $request)
    {
        return $this->schemaService->generateNavSchema($menu, $request);
    }

    public function getFaqSchema(array $faq)
    {
        return $this->schemaService->generateFaqSchema($faq);
    }

    public function getProfessionalServiceSchema(array $settings)
    {
        return $this->schemaService->getProfessionalServiceSchema($settings);
    }

    public function getHowToSchema(string $heading, array $steps)
    {
        return $this->schemaService->getHowToSchema($heading, $steps);
    }


    public function getRandomServices($limit, $exclude = null)
    {
        return $this->em->getRepository(Service::class)->findRandom($limit, $exclude);
    }


    public function getServiceSchema($name, $description)
    {
        return $this->schemaService->getServiceSchema($name, $description);
    }

    public function getCollectionSchema(string $name, string $description, string $url, $articles)
    {
        return $this->schemaService->getCollectionSchema($name, $description, $url, $articles);
    }


    public function transformSeoMeta($string, $options)
    {
        foreach ($options as $toReplace => $replacer) {
            $string = str_replace($toReplace, $replacer, $string);
        }

        return $string;
    }

    public function delete404Links($content, Request $request)
    {

        $baseUrl = $request->getSchemeAndHttpHost();
        $baseUrlHttps = str_replace('http://', 'https://', $request->getSchemeAndHttpHost());

        if ($content) {
            $crawler = new Crawler($content, null, $baseUrlHttps);
            $a = $crawler->filter('a');
            if ($a->count() > 0) {
                $a->each(
                    function (Crawler $node) use ($baseUrl, $baseUrlHttps, $request) {

                        $link = $node->link()->getUri();
                        $linkBaseUrl = 'https://'.parse_url($link, PHP_URL_HOST);

                        if ($linkBaseUrl == $baseUrl || $linkBaseUrl == $baseUrlHttps) {
                            $link = str_replace([$baseUrl, $baseUrlHttps], '', $link);

                            try {
                                $route = $this->router->match($link);
                                $exists = $this->urlService->getUrl($route);

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
            }
        } else {
            return $content;
        }

        return $content;
    }

    public function createTechPageContent(string $content) : TableOfContentService
    {
        $content = $this->contentParser->renderTechPageContent($content);
        $tableOfContentService = new TableOfContentService($content);
        $tableOfContentService->create();

        return $tableOfContentService;
    }
}