<?php

namespace App\Twig;

use App\Controller\Admin\SettingsController;
use App\Repository\Admin\OptionRepository;
use App\Service\Admin\SettingsService;
use App\Service\Frontend\SchemaService;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;

class FrontExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        private readonly OptionRepository $optionRepository,
        private readonly SettingsService $settingsService,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly SchemaService $schemaService
    )
    {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('render_image', [FrontRuntime::class, 'renderImage']),
            new TwigFunction('uploaded_asset', [FrontRuntime::class, 'uploadedAsset']),
            new TwigFunction('reading_time', [FrontRuntime::class, 'getReadingTime']),
            new TwigFunction('option', [FrontRuntime::class, 'getOption']),
            new TwigFunction('socials', [FrontRuntime::class, 'getSocials']),
            new TwigFunction('get_field', [FrontRuntime::class, 'getField']),
            new TwigFunction('faq_schema', [FrontRuntime::class, 'generateFaqScheme']),
            new TwigFunction('toc', [FrontRuntime::class, 'getTOC']),
            new TwigFunction('percents', [FrontRuntime::class, 'getPercentage']),
            new TwigFunction('seo_meta', [FrontRuntime::class, 'getSeoMeta']),
            new TwigFunction('nav', [FrontRuntime::class, 'getNav']),
            new TwigFunction('nav_schema', [FrontRuntime::class, 'getNavSchema']),
            new TwigFunction('faq_schema', [FrontRuntime::class, 'getFaqSchema']),
            new TwigFunction('prof_schema', [FrontRuntime::class, 'getProfessionalServiceSchema']),
            new TwigFunction('how_to_schema', [FrontRuntime::class, 'getHowToSchema']),
            new TwigFunction('services_tabs', [FrontRuntime::class, 'getServicesTabs']),
            new TwigFunction('random_services', [FrontRuntime::class, 'getRandomServices']),
            new TwigFunction('random_posts', [FrontRuntime::class, 'getRandomPosts']),
            new TwigFunction('collection_schema', [FrontRuntime::class, 'getCollectionSchema']),
            new TwigFunction('create_tech_page_content', [FrontRuntime::class, 'createTechPageContent'])
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('ucwords', [FrontRuntime::class, 'ucwordsFilter']),
            new TwigFilter('shuffle', [FrontRuntime::class, 'shuffle']),
            new TwigFilter('word_count', [FrontRuntime::class, 'getWordCount']),
            new TwigFilter('html_decode', [FrontRuntime::class, 'html_decode']),
            new TwigFilter('filename', [FrontRuntime::class, 'filename']),
            new TwigFilter('int', [FrontRuntime::class, 'intVal']),
            new TwigFilter('render_blocks', [FrontRuntime::class, 'renderBlocks']),
            new TwigFilter('toc', [FrontRuntime::class, 'toc']),
            new TwigFilter('to_https', [FrontRuntime::class, 'toHttps']),
            new TwigFilter('cat_url', [FrontRuntime::class, 'categoryUrlById']),
            new TwigFilter('transform_seo_meta', [FrontRuntime::class, 'transformSeoMeta']),
            new TwigFilter('delete_404', [FrontRuntime::class, 'delete404Links']),
        ];
    }

    public function getGlobals(): array
    {
        $settings = $this->settingsService->getArrayByArea(SettingsController::OPTIONS_AREA);
        $siteUrl = $this->urlGenerator->generate('home', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $settings['url'] = $siteUrl;

        return [
            'home_page_id' => $this->optionRepository->findBy(['slug' => 'home_page']),
            'settings' => $settings,
            'organizationSchema' => $this->schemaService->generateOrganizationSchema($settings)
        ];
    }
}