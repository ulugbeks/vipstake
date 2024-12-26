<?php

namespace App\Controller\Admin;

use App\Attribute\Admin\Editable;
use App\Attribute\Admin\Nav;
use App\Entity\Frontend\Page;
use App\Entity\Frontend\VipReview;
use App\Form\PageType;
use App\Form\VipReviewType;
use App\Handlers\UploadHandler;
use App\Helpers\ClassHelper;
use App\PageBuilder\Service\PageBuilderService;
use App\Repository\Admin\MetaRepository;
use App\Repository\Admin\OptionRepository;
use App\Repository\Frontend\PageRepository;
use App\Repository\Frontend\VipReviewRepository;
use App\Service\Admin\FieldsService;
use App\Service\Admin\SettingsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/review', name: 'admin_review_')]
class VipReviewController extends AdminController
{
    const OPTIONS_AREA = 'vip_review';

    #[Route('/', name: 'list')]
    #[Nav(icon: '/admin/img/page.svg', name: 'Review', order: '2', route: '/admin/review/')]
    public function index(VipReviewRepository $repository): Response
    {
        return $this->render('admin/review/index.html.twig', [
            'pages' => $repository->findAll(),
            'pageName' => 'Reviews',
        ]);
    }

    #[Route('/add/', name: 'add')]
    #[Editable(VipReview::class, Editable::ACTION_ADD)]
    public function add(Request $request, EntityManagerInterface $em, UploadHandler $uploadHandler)
    {
        $form = $this->createForm(VipReviewType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var VipReview $review */
            $review = $form->getData();
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['thumbnail']->getData();

            if ($uploadedFile) {
                $thumb = $uploadHandler->uploadImage($uploadedFile);
                $review->setThumbnail($thumb);
            }

            $em->persist($review);
            $em->flush();

            return $this->redirectToRoute('admin_review_edit', ['id' => $review->getId()]);
        }

        return $this->render('admin/review/item.html.twig', [
            'reviewForm' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}/', name: 'edit')]
    #[Editable(VipReview::class, Editable::ACTION_EDIT)]
    public function edit(
        VipReview $review,
        Request $request,
        EntityManagerInterface $em,
        UploadHandler $uploadHandler
    ) {
        $form = $this->createForm(VipReviewType::class, $review);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var VipReview $review */
            $review = $form->getData();
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['thumbnail']->getData();

            if ($uploadedFile) {
                $thumb = $uploadHandler->uploadImage($uploadedFile);
                $review->setThumbnail($thumb);
            }

            $em->flush();

            return $this->redirectToRoute('admin_review_edit', ['id' => $review->getId()]);
        }

        return $this->render('admin/review/item.html.twig', [
            'reviewForm' => $form->createView(),
            'review' => $review,
        ]);
    }

    #[Route('/delete/{id}/', name: 'delete')]
    #[Editable(VipReview::class, Editable::ACTION_DELETE)]
    public function delete(VipReview $review, EntityManagerInterface $em)
    {
        $em->remove($review);
        $em->flush();

        return $this->redirectToRoute('admin_review_list');
    }

    #[Route('/recover/{id}/', name: 'recover')]
    #[Editable(VipReview::class, Editable::ACTION_RECOVER)]
    public function recover(VipReview $review, EntityManagerInterface $em)
    {
        $review->setDeletedAt(null);
        $em->flush();

        return $this->redirectToRoute('admin_review_list');
    }

    #[Route('/archive/', name: 'archive')]
    public function archive(VipReviewRepository $repository): Response
    {
        return $this->render('admin/review/index.html.twig', [
            'pages' => $repository->getTrashed(),
            'pageName' => 'Reviews archive',
            'isArchive' => true,
        ]);
    }

    #[Route('/pb/{id}', name: 'pagebuilder')]
    public function pageBuilder(VipReview $review, PageBuilderService $pageBuilder)
    {
        return $this->render('page_builder/templates/review/review.html.twig', [
            'pageBuilder' => $pageBuilder,
            'review' => $review,
            'pageId' => $review->getId(),
            'pageType' => ClassHelper::escapeClassName(VipReview::class),
        ]);
    }

    #[Route('/listing-fields', 'listing_fields')]
    public function listingFields(OptionRepository $optionRepository, Request $request): Response
    {
        self::registerListingOptions($this->em);

        if ($request->isMethod("POST")) {
            $options = $request->get('options');

            foreach ($options as $id => $value) {
                if (is_array($value)) {
                    $value = json_encode($value);
                }

                $option = $optionRepository->find($id);
                $option->setValue($value);
                $this->em->flush();

                return $this->redirectToRoute('admin_review_listing_fields');
            }
        }

        return $this->render('admin/review/listing_fields.html.twig', [
            'pageName' => 'Vip Review Lobby Fields',
            'options' => $optionRepository->findBy(['area' => self::OPTIONS_AREA]),
        ]);
    }

    private static function registerListingOptions(EntityManagerInterface $entityManager)
    {
        $options = [
            'vip_review_h1' => [
                'H1',
                SettingsService::OPTION_TEXT,
                self::OPTIONS_AREA,
            ],
            'vip_review_uh1' => [
                'Text under H1',
                SettingsService::OPTION_TEXTAREA,
                self::OPTIONS_AREA,
            ],
            'vip_review_seo_title' => [
                'Seo Title',
                SettingsService::OPTION_TEXT,
                self::OPTIONS_AREA,
            ],
            'vip_review_seo_description' => [
                'Seo Description',
                SettingsService::OPTION_TEXTAREA,
                self::OPTIONS_AREA,
            ],
        ];

        SettingsService::registerOptions($options, $entityManager);
    }
}
