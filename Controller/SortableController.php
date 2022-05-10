<?php

namespace Orkestra\EaSortable\Controller;

use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\EA;
use EasyCorp\Bundle\EasyAdminBundle\Configuration\ConfigManager;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Annotation\Route;

class SortableController extends AbstractController
{
    /**
     * @Route("/admin/sort/{property}", name="easyadmin.sortable.sort")
     */
    public function sort(Request $request, EntityManagerInterface $em, string $property): RedirectResponse
    {
        $entity = $request->get('entity');
        $id = $request->get('id');
        $position = (int)$request->get('position');
        $adminContext = $request->attributes->get(EA::CONTEXT_REQUEST_ATTRIBUTE);
        $object = $em->find($request->query->get('entityFqsn'), $id);

        if (null === $object) {
            throw $this->createNotFoundException();
        }

        $accessor = PropertyAccess::createPropertyAccessor();
        $accessor->setValue($object, $property, $position);
        $em->flush();

        return $this->redirectToRoute('easyadmin', [
            'action' => 'list',
            'entity' => $entity
        ]);
    }
}
