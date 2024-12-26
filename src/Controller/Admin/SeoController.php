<?php

namespace App\Controller\Admin;

use App\Attribute\Admin\Editable;
use App\Attribute\Admin\Nav;
use App\Entity\Frontend\Model;
use App\Entity\Frontend\Page;
use App\Entity\Frontend\SeoTemplates;
use App\Trait\Frontend\SeoMetaTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SeoController extends AdminController
{

    #[Route('/admin/seo', name: 'admin_seo')]
    #[Nav(icon: '/admin/img/seo.svg', name: 'SEO', order: '5')]
    public function index()
    {
        return $this->render('admin/seo/index.html.twig', [
            'pageName' => 'SEO',
        ]);
    }

    #[Route('/admin/seo/robots', name: 'admin_seo_robots')]
    public function robots(Request $request)
    {
        $path = $this->getParameter('public_dir').'/robots.txt';
        $content = file_get_contents($path);

        if ($request->get('robots')) {
            $robots = $request->get('robots');
            file_put_contents($path, $robots);

            return $this->redirectToRoute('admin_seo_robots');
        }

        return $this->render('admin/seo/robots.html.twig', [
            'content' => $content,
        ]);
    }

    #[Route('/admin/seo/meta', name: 'admin_seo_meta')]
    public function meta(Request $request)
    {
        if ($request->get('meta')) {
            foreach ($request->get('meta') as $item) {
                $template = $this->em->getRepository(SeoTemplates::class)->findOneBy(['entity' => $item['class']]
                ) ?? new SeoTemplates();
                $template->setEntity($item['class']);

                $template->setTitle($item['title']);
                $template->setDescription($item['description']);


                $this->em->persist($template);
            }

            $this->em->flush();

            $this->redirectToRoute('admin_seo_meta');
        }


        return $this->render('admin/seo/meta.html.twig', [
            'seoEntities' => $this->getSeoEntities(),
        ]);
    }

    private function getSeoEntities()
    {
        $entities = $this->em->getMetadataFactory()->getAllMetadata();
        $seoEntities = [];

        foreach ($entities as $entity) {
            if (in_array(SeoMetaTrait::class, class_uses($entity->name))) {
                $baseName = basename(str_replace('\\', '/', $entity->name));
                $label = preg_replace('/(?<!\ )[A-Z]/', ' $0', $baseName);

                $seoEntities[] = [
                    'label' => trim($label),
                    'class' => $entity->name,
                    'fields' => $this->em->getRepository(SeoTemplates::class)->findOneBy(['entity' => $entity->name]),
                ];
            }
        }

        return $seoEntities;
    }
}