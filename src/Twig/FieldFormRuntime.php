<?php

namespace App\Twig;

use App\Field\FieldItem;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DomCrawler\Crawler;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Extension\RuntimeExtensionInterface;

class FieldFormRuntime implements RuntimeExtensionInterface
{
    private ParameterBagInterface $container;
    private Environment $twig;

    public function __construct(ParameterBagInterface $container, Environment $twig)
    {
        $this->container = $container;
        $this->twig = $twig;
    }

    public function getFieldType($field)
    {
        $field = new \ReflectionClass($field->getType());

        return ($field->newInstance())->getType();
    }

    public function getLocales()
    {
        return explode('|', $this->container->get('locales'));
    }

    public function getFieldsPrototype(ArrayCollection $fields)
    {
        $prototype = '';

        foreach ($fields as $field) {
            $prototype .= $this->twig->render(
                'admin/field_generator/field_type/'.$this->getFieldType($field->getField()).'.html.twig',
                [
                    'field' => $field,
                    'value' => '',
                ]
            );
        }

        $prototype = $this->wrapRepeaterRow($prototype);

        return $prototype;
    }

    public function renderField(FieldItem $field)
    {
        $prototype = $this->twig->render(
            'admin/field_generator/field_type/'.$this->getFieldType($field->getField()).'.html.twig',
            [
                'field' => $field,
                'value' => $field->getValue(),
            ]
        );


        return $prototype;
    }

    public function renderRepeaterRow(FieldItem $fieldItem)
    {
        $prototype = '';

        if($fieldItem->getMeta()){
            $values = json_decode($fieldItem->getMeta()->getContent(), true);

            if ($values) {
                $prototype = $this->renderCollection($fieldItem, $values);
            }
        }


        return $prototype;
    }

    public function renderCollection(FieldItem $fieldItem, $values)
    {
        $prototype = '';

        for ($i = 0; $i < count($values); $i++) {
            $row = '';
            /** @var FieldItem $child */
            foreach ($fieldItem->getChildren() as $child) {
                $field = $this->twig->render(
                    'admin/field_generator/field_type/'.$this->getFieldType($child->getField()).'.html.twig',
                    [
                        'field' => $child,
                        'value' => $values[$i][$child->getField()->getLabel()] ?? null,
                    ]
                );
                $row .= str_replace('__index__', $i, $field);
            }

            $prototype .= $this->wrapRepeaterRow($row);
        }

        return $prototype;
    }

    private function wrapRepeaterRow($content)
    {
        $delete = '<button type="button" class="repeater__row-delete">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_7724_9917)">
                            <path d="M1.2 4.00635H14.8" stroke="#FF4D4F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M5.1328 4.00005V3.63365C5.1328 2.88213 5.43134 2.16139 5.96274 1.62999C6.49414 1.09859 7.21488 0.800049 7.9664 0.800049C8.71791 0.800049 9.43865 1.09859 9.97006 1.62999C10.5015 2.16139 10.8 2.88213 10.8 3.63365V4.00005" stroke="#FF4D4F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6.5 8V11" stroke="#FF4D4F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9.5 8V11" stroke="#FF4D4F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M2.79999 6C2.38577 6 2.04999 6.33579 2.04999 6.75V13.6C2.04999 14.2233 2.29758 14.821 2.73829 15.2617C3.179 15.7024 3.77673 15.95 4.39999 15.95H11.6C12.2232 15.95 12.821 15.7024 13.2617 15.2617C13.7024 14.821 13.95 14.2233 13.95 13.6V6.75C13.95 6.33579 13.6142 6 13.2 6C12.7858 6 12.45 6.33579 12.45 6.75V13.6C12.45 13.8254 12.3604 14.0416 12.201 14.201C12.0416 14.3604 11.8254 14.45 11.6 14.45H4.39999C4.17455 14.45 3.95835 14.3604 3.79895 14.201C3.63954 14.0416 3.54999 13.8254 3.54999 13.6V6.75C3.54999 6.33579 3.2142 6 2.79999 6Z" fill="#FF4D4F"/>
                        </g>
                        <defs>
                            <clipPath id="clip0_7724_9917">
                                <rect width="16" height="16" fill="white"/>
                            </clipPath>
                        </defs>
                    </svg>
                </button>';

        return "<div class='field__repeater-row'>".$content.$delete."</div>";
    }
}