<?php

namespace App\Service\Admin;

use App\Controller\Admin\SettingsController;
use App\Controller\Admin\VipReviewController;
use App\Controller\Admin\WriterController as WriterAdmin;
use App\Entity\Admin\Option;
use App\Repository\Admin\OptionRepository;
use Doctrine\ORM\EntityManagerInterface;

class SettingsService
{
    const OPTION_TEXT = 'text';
    const OPTION_TEXTAREA = 'textarea';
    const OPTION_ARRAY = 'array';
    const OPTION_CONTENT = 'content';

    public function __construct(
        private readonly OptionRepository $optionRepository,
        private readonly EntityManagerInterface $em
    ) {
    }

    public function init()
    {
        self::registerOptions(self::getOptions(), $this->em);
    }

    public static function getOptions()
    {
        return [
            'home_page' => ['Home page', self::OPTION_TEXT, SettingsController::OPTIONS_AREA],
            'address' => ['Address', self::OPTION_TEXT, SettingsController::OPTIONS_AREA],
            'phone' => ['Phone', self::OPTION_TEXT, SettingsController::OPTIONS_AREA],
            'email' => ['Email', self::OPTION_TEXT, SettingsController::OPTIONS_AREA],
            'facebook' => ['Facebook', self::OPTION_TEXT, SettingsController::OPTIONS_AREA],
            'instagram' => ['Instagram', self::OPTION_TEXT, SettingsController::OPTIONS_AREA],
            'linked_in' => ['LinkedIn', self::OPTION_TEXT, SettingsController::OPTIONS_AREA],
            'site_name' => ['Site name', self::OPTION_TEXT, SettingsController::OPTIONS_AREA],
            'footer_text' => ['Footer text', self::OPTION_TEXTAREA, SettingsController::OPTIONS_AREA],
            'site_description' => ['Site Description', self::OPTION_TEXTAREA, SettingsController::OPTIONS_AREA],
            'disclosure' => ['21+ disclosure', self::OPTION_TEXTAREA, SettingsController::OPTIONS_AREA],
        ];
    }

    public static function registerOptions(array $options, EntityManagerInterface $em)
    {
        foreach ($options as $slug => $values) {
            $option = new Option();
            $option->setSlug($slug)
                ->setLabel($values[0])
                ->setType($values[1])
                ->setArea($values[2]);


            if (!$em->getRepository(Option::class)->findOneBy(['slug' => $slug])) {
                $em->persist($option);
            }
        }

        $em->flush();
    }

    public static function registerOption($slug, $label, $type, $area, EntityManagerInterface $em)
    {
        $option = new Option();
        $option->setSlug($slug)
            ->setLabel($label)
            ->setType($type)
            ->setArea($area);


        if (!$em->getRepository(Option::class)->findOneBy(['slug' => $slug])) {
            $em->persist($option);
            $em->flush();
        }
    }

    public function getArrayByArea(string $area): array
    {
        $content = [];
        $options = $this->optionRepository->findBy(['area' => $area]);

        foreach ($options as $option) {
            $value = $option->getValue();

            if ($option->getType() === SettingsService::OPTION_ARRAY) {
                $value = json_decode($value, true);
            }

            $content[$option->getSlug()] = $value;
        }

        return $content;
    }
}