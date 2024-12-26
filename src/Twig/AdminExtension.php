<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AdminExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('sideNav', [AdminRuntime::class, 'getSideNav']),
            new TwigFunction('edit_url', [AdminRuntime::class, 'getEditUrl']),
            new TwigFunction('delete_url', [AdminRuntime::class, 'getDeleteUrl']),
            new TwigFunction('recover_url', [AdminRuntime::class, 'getRecoverUrl']),
            new TwigFunction('uploads_path', [AdminRuntime::class, 'getUploadedAsset']),
            new TwigFunction('field_group', [AdminRuntime::class, 'getFieldGroup']),
            new TwigFunction('debug', [AdminRuntime::class, 'debug']),
            new TwigFunction('nav_route', [AdminRuntime::class, 'getNavItemRoute']),
            new TwigFunction('get_pages', [AdminRuntime::class, 'getPages']),
            new TwigFunction('option_template_exists', [AdminRuntime::class, 'isOptionTemplateExists']),
            new TwigFunction('field_type', [FieldFormRuntime::class, 'getFieldType']),
            new TwigFunction('get_locales', [FieldFormRuntime::class, 'getLocales']),
            new TwigFunction('fields_prototype', [FieldFormRuntime::class, 'getFieldsPrototype']),
            new TwigFunction('child_field', [FieldFormRuntime::class, 'renderChildField']),
            new TwigFunction('render_field', [FieldFormRuntime::class, 'renderField']),
            new TwigFunction('repeater_row', [FieldFormRuntime::class, 'renderRepeaterRow']),
            new TwigFunction('option_label', [AdminRuntime::class, 'getOptionLabel']),
            new TwigFunction('choice_val', [AdminRuntime::class, 'fixChoiceValue']),
            new TwigFunction('get_ids', [AdminRuntime::class, 'getIds']),
            new TwigFunction('get_string_slug', [AdminRuntime::class, 'transformStringSlug']),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('decode', [AdminRuntime::class, 'decode']),
        ];
    }
}