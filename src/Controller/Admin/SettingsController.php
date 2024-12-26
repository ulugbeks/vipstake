<?php

namespace App\Controller\Admin;

use App\Attribute\Admin\Nav;
use App\Repository\Admin\OptionRepository;
use App\Service\Admin\SettingsService;
use App\Translator\OptionsTranslator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SettingsController extends AdminController
{
    const OPTIONS_AREA = 'settings';

    #[Route('/admin/settings', name: 'admin_settings')]
    #[Nav(icon: '/admin/img/settings.svg', name: 'Settings', order: '6')]
    public function index(OptionRepository $optionRepository, Request $request): Response
    {
        if($request->get('options')){
            $options = $request->get('options');

            foreach ($options as $id => $value){
                $option = $optionRepository->find($id);
                $option->setValue($value);
            }

            $this->em->flush();

            return $this->redirectToRoute('admin_settings');
        }

        return $this->render('admin/settings/index.html.twig', [
            'pageName' => 'Settings',
            'options' => $optionRepository->findBy(['area' => self::OPTIONS_AREA])
        ]);
    }

    #[Route('/admin/settings/translate', name: 'admin_settings_translate')]
    public function translateOptions(OptionsTranslator $optionsTranslator, Request $request) : Response
    {
        $strings = $optionsTranslator->getOptionsTranslations('options');

        if ($request->get('translations')) {
            $translations = $request->get('translations');
            $optionsTranslator->saveTranslations($translations, 'options');

            return $this->redirectToRoute('admin_settings_translate');
        }

        return $this->render('admin/settings/translations.html.twig', [
            'options' => $strings
        ]);
    }
}
