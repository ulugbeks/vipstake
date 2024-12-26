<?php

namespace App\Controller\Admin;

use App\Attribute\Admin\Editable;
use App\Attribute\Admin\Nav;
use App\Entity\Admin\Field;
use App\Entity\Admin\FieldsGroup;
use App\Form\FieldsGroupCombinedType;
use App\Form\FieldsGroupType;
use App\Repository\Admin\FieldsGroupRepository;
use App\Service\Admin\FieldsService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FieldsController extends AdminController
{
    #[Route('/admin/fields', name: 'admin_fields')]
    #[Nav(icon: '/admin/img/fields.svg', name: 'Fields', order: '3', roles: ['ROLE_SUPERADMIN'])]
    #[Security('is_granted("ROLE_SUPERADMIN")')]
    public function index(FieldsGroupRepository $repository): Response
    {
        return $this->render('admin/fields/index.html.twig', [
            'pageName' => 'Custom Fields Groups',
            'controller_name' => 'FieldsController',
            'fields' => $repository->findAll()
        ]);
    }

    #[Route('/admin/fields/add', name: 'admin_fields_add')]
    public function addGroup(Request $request): Response
    {
        $form = $this->createForm(FieldsGroupType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fieldsGroup = $form->getData();
            $this->em->persist($fieldsGroup);
            $this->em->flush();

            return $this->redirectToRoute('admin_fields');
        }

        return $this->render('admin/fields/group.html.twig', [
            'fieldsGroupForm' => $form->createView()
        ]);
    }

    #[Route('/admin/fields/edit/{id}', name: 'admin_fields_edit')]
    #[Editable(FieldsGroup::class, Editable::ACTION_EDIT)]
    public function editGroup(FieldsGroup $fieldsGroup, Request $request, FieldsService $fieldsService)
    {
        $form = $this->createForm(FieldsGroupCombinedType::class);
        $form->get('fields_form')->setData([
            'fieldSet' => $fieldsGroup->getFields()
        ]);
        $form->get('fields_group_form')->setData($fieldsGroup);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $fields = $form->get('fields_form')->getData();
            $this->updateFields($fieldsService, $fieldsGroup, $fields);
            $fieldsGroup = $form->get('fields_group_form')->getData();
            $this->em->flush();
            return $this->redirectToRoute('admin_fields_edit', ['id' => $fieldsGroup->getId()]);
        }

        return $this->render('admin/fields/fields.html.twig', [
            'fieldsForm' => $form->createView(),
            'fieldsGroup' => $fieldsGroup->getId()
        ]);
    }

    #[Route('/admin/fields/delete/{id}', name: 'admin_fields_delete')]
    #[Editable(FieldsGroup::class, Editable::ACTION_DELETE)]
    public function delete(FieldsGroup $fieldsGroup){
        $this->em->remove($fieldsGroup);
        $this->em->flush();
        return $this->redirectToRoute('admin_fields');
    }

    private function updateFields(FieldsService $fieldsService, $fieldsGroup, $fields)
    {
        $fieldsService->getFieldsToDelete($fieldsGroup, $fields['fieldSet']);
        /** @var Field $field */
        foreach ($fields['fieldSet'] as $field) {
            $fieldsService->setParents($field, $fields['fieldSet']);
            $field->setFieldGroup($fieldsGroup);
            $this->em->persist($field);
        }
    }
}
