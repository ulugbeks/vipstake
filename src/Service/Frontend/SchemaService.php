<?php

namespace App\Service\Frontend;

use App\Controller\Admin\SettingsController;
use App\Entity\Frontend\Article;
use App\Entity\Frontend\NavItem;
use App\Entity\Frontend\NavMenu;
use App\Entity\Frontend\Service;
use App\Entity\Frontend\Writer;
use App\Helpers\Helper;
use App\Service\Admin\NavItemService;
use App\Service\Admin\SettingsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SchemaService
{
    private $settings;

    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly NavItemService $navItemService,
        private readonly SettingsService $settingsService
    ) {
        $this->settings = $this->settingsService->getArrayByArea(SettingsController::OPTIONS_AREA);
    }

    public function generateOrganizationSchema(array $settings)
    {
        $organizationSchema = [
            "@context" => "https://schema.org",
            "@type" => "Organization",
            "name" => $settings['site_name'],
            "url" => $settings['url'],
            "logo" => $settings['url'].'frontend/logo.svg',
            "description" => $settings['site_description'],
            "email" => $settings['email'],
        ];

        if ($settings['facebook']) {
            $organizationSchema['sameAs'][] = $settings['facebook'];
        }

        if ($settings['instagram']) {
            $organizationSchema['sameAs'][] = $settings['instagram'];
        }

        return json_encode($organizationSchema);
    }

    public function generateNavSchema(NavMenu $menu, Request $request)
    {
        $navMenuSchema = array(
            "@context" => "https://schema.org",
            "@type" => "SiteNavigationElement",
            "name" => "Main Navigation",
            "url" => $this->urlGenerator->generate('home', [], UrlGeneratorInterface::ABSOLUTE_URL),
            // URL to the main page or home page
            "hasPart" => array(),
        );

        /** @var NavItem $item */
        foreach ($menu->getAllItems() as $item) {
            if ($item->getUrl() !== '#') {
                $navMenuSchema['hasPart'][] = $this->createSiteNavigationElement($item, $request);
            }
        }

        return json_encode($navMenuSchema, JSON_PRETTY_PRINT);
    }

    private function createSiteNavigationElement(NavItem $item, Request $request)
    {
        $navElement = [
            "@type" => "SiteNavigationElement",
            "name" => $item->getName(),
            "url" => $this->navItemService->generateRoute($item, $request, UrlGeneratorInterface::ABSOLUTE_URL),
        ];

        return $navElement;
    }

    public function generateFaqSchema($faq)
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

    public function getProfessionalServiceSchema(array $settings)
    {
        $schema = [
            "@context" => "http://www.schema.org",
            "@type" => "ProfessionalService",
            "name" => $settings['site_name'],
            "url" => $settings['url'],
            "priceRange" => "$$$",
            "logo" => $settings['url'].'frontend/logo.svg',
            "image" => $settings['url'].'frontend/logo.svg',
            "description" => $settings['site_description'],
            "address" => [
                "@type" => "PostalAddress",
                "streetAddress" => "747 Virginia Ave NE",
                "addressLocality" => "Atlanta",
                "addressRegion" => "Georgia",
                "postalCode" => "30306",
                "addressCountry" => "United States",
            ],
            "openingHours" => "Mo, Tu, We, Th, Fr, Sa, Su -",

        ];

        if ($settings['phone']) {
            $schema['telephone'] = $settings['phone'];
            $schema["contactPoint"] = [
                "@type" => "ContactPoint",
                "contactType" => "Telephone",
            ];
        }

        return json_encode($schema, JSON_PRETTY_PRINT);
    }

    public function getHowToSchema(string $heading, array $steps)
    {
        $schema = [
            "@context" => "https://schema.org/",
            "@type" => "HowTo",
            "name" => $heading,
            "supply" => [
                [
                    "@type" => "HowToSupply",
                    "name" => "Paper Instructions",
                ],
                [
                    "@type" => "HowToSupply",
                    "name" => "Laptop/Smartphone",
                ],
            ],
            "tool" => [
                [
                    "@type" => "HowToTool",
                    "name" => "Credit Card",
                ],
                [
                    "@type" => "HowToTool",
                    "name" => "Website ".$this->settings['site_name'],
                ],
            ],
        ];

        foreach ($steps as $step) {
            if (isset($step['icon'])) {
                $step['image'] = $step['icon'];
            }

            if (isset($step['heading'])) {
                $step['caption'] = $step['heading'];
            }

            $schema['step'][] = [
                "@type" => "HowToStep",
                "text" => $step['text'],
                "image" => $this->urlGenerator->generate(
                        'home',
                        [],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    ).$step['image'],
                "name" => $step['caption'],
            ];
        }

        return json_encode($schema, JSON_PRETTY_PRINT);
    }

    public function getServiceSchema(string $name, string $description)
    {
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "Product",
            "aggregateRating" => [
                "@type" => "AggregateRating",
                "ratingValue" => Helper::getRandomFloat(4.7, 5.0),
                "reviewCount" => mt_rand(3100, 3300),
            ],
            "description" => $description,
            "name" => $name,
            "image" => $this->urlGenerator->generate(
                    'home',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ).'frontend/logo.svg',
        ];

        return json_encode($schema, JSON_PRETTY_PRINT);
    }

    /**
     * @param string $name
     * @param string $description
     * @param string $url
     * @param Article[] $articles
     * @return string
     */
    public function getCollectionSchema(string $name, string $description, string $url, $articles)
    {
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "CollectionPage",
            "name" => $name,
            "description" => $description,
            "url" => $url,
        ];

        $loopIndex = 1;
        foreach ($articles as $article) {
            $schema['mainEntity'][] = [
                "@type" => "ListItem",
                "position" => $loopIndex,
                "item" => [
                    "@type" => "Article",
                    "name" => $article->getTitle(),
                    "url" => $this->urlGenerator->generate('article', [
                        'slug' => $article->getSlug(),
                        'category' => $article->getCategory()->getSlug(),
                    ], UrlGeneratorInterface::ABSOLUTE_URL),
                ],
            ];

            $loopIndex++;
        }

        return json_encode($schema, JSON_PRETTY_PRINT);
    }

    public function getArticleSchema(Article $article)
    {
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "Article",
            "mainEntityOfPage" => [
                "@type" => "WebPage",
                "@id" => $this->urlGenerator->generate(
                    'article',
                    ['slug' => $article->getSlug(), 'category' => $article->getCategory()->getSlug()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
            ],
            "headline" => $article->getH1() ?? $article->getTitle(),
            "description" => $article->getExcerpt(),
            "image" => $this->urlGenerator->generate(
                    'home',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ).'uploads/media/'.$article->getThumbnail(),
            "author" => [
                "@type" => "Person",
                "name" => $article->getAuthor()->getName(),
                "url" => $this->urlGenerator->generate(
                    'writer',
                    ['slug' => $article->getAuthor()->getSlug()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
            ],
            "publisher" => [
                "@type" => "Organization",
                "name" => $this->settings['site_name'],
                "logo" => [
                    "@type" => "ImageObject",
                    "url" => $this->urlGenerator->generate(
                            'home',
                            [],
                            UrlGeneratorInterface::ABSOLUTE_URL
                        ).'frontend/img/logo.svg',
                ],
            ],
            "datePublished" => $article->getCreatedAt()->format('Y-m-d'),
            "dateModified" => $article->getUpdatedAt()->format('Y-m-d'),
        ];


        return json_encode($schema, JSON_PRETTY_PRINT);
    }

    public function getWriterSchema(Writer $writer)
    {
        $schema = [
            "@context" => "https://schema.org/",
            "@type" => "Person",
            "memberof" => [
                "@type" => "Organization",
                "name" => $this->settings['site_name'],
                "url" => $this->urlGenerator->generate('home', [], UrlGeneratorInterface::ABSOLUTE_URL),
            ],
            "name" => $writer->getName(),
            "image" => $this->urlGenerator->generate(
                    'home',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ).'uploads/media/'.$writer->getThumbnail(),
            "url" => $this->urlGenerator->generate(
                'writer',
                ['slug' => $writer->getSlug()],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
            "jobTitle" => "Writer",
        ];

        return json_encode($schema, JSON_PRETTY_PRINT);
    }

    public function getBreadcrumbsSchema($breadcrumbs){
       $schema = [
            "@context" => "https://schema.org",
            "@type" => "BreadcrumbList",
        ];

       $loopIndex = 1;
       foreach ($breadcrumbs as $breadcrumb){
           $schema['itemListElement'][] = [
               "@type" => "ListItem",
               "position" => $loopIndex,
               "name" => $breadcrumb->text,
               "item" => $breadcrumb->url
           ];

           $loopIndex++;
       }

       return json_encode($schema, JSON_PRETTY_PRINT);
    }
}