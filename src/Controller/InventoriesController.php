<?php

namespace App\Controller;

use App\Form\ImportType;
use App\Message\ImportInventoriesMessage;
use App\Service\ProductImportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class InventoriesController extends AbstractController
{
    #[Route('/inventories', name: 'app_inventories')]
    public function index(): Response
    {
        return $this->render('inventories/index.html.twig', [
            'controller_name' => 'InventoriesController',
        ]);
    }

    #[Route('/import/inventories', name: 'import_inventories')]
    public function importInventories(Request $request, MessageBusInterface $messageBus, ProductImportService $productImportService): Response
    {
        $form = $this->createForm(ImportType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $jsonFile = $form->get('jsonFile')->getData();
            if ($jsonFile) {
                $jsonData = file_get_contents($jsonFile->getPathname());
                $jsonDataArray = json_decode($jsonData, true);
                $messageBus->dispatch(new ImportInventoriesMessage($jsonDataArray));
                return $this->redirectToRoute('products');
            }
        }

        return $this->render('product/import_inventories.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/erp/inventories', name: 'app_inventories', methods:['POST'])]
    public function inventories(Request $request, MessageBusInterface $messageBus): Response
    {
        $data = json_decode($request->getContent(), true);
        $messageBus->dispatch(new ImportInventoriesMessage($data));
        return new Response('Data received and dispatched successfully', Response::HTTP_OK);
    }
}
