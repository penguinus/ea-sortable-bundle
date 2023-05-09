<?php

namespace Orkestra\EaSortable\Controller;

use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\EA;
use EasyCorp\Bundle\EasyAdminBundle\Configuration\ConfigManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Annotation\Route;

class SortableController extends AbstractController
{
    /**
     * @Route("/admin/sort/{property}/{fqcn}", name="easyadmin.sortable.sort")
     */
    public function sort(
        Request $request,
        EntityManagerInterface $em,
        string $property,
        string $fqcn
    ): Response
    {

        $entityFqcn = $fqcn;

        $id = $request->get('id');
        $position = (int)$request->get('position');
        $position++;

        $adminContext = $request->attributes->get(EA::CONTEXT_REQUEST_ATTRIBUTE);
        $object = $em->find($entityFqcn, $id);

        if (null === $object) {
            throw $this->createNotFoundException();
        }

        $accessor = PropertyAccess::createPropertyAccessor();
        $accessor->setValue($object, $property, $position);

        $em->persist($object);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}

