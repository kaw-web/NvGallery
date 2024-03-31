<?php

namespace App\Controller;

use App\Form\ImportType;
use App\Message\ImportProductAndStockDataMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    #[Route('/import/product', name: 'import_product')]
    public function importProducts(Request $request, MessageBusInterface $messageBus): Response
    {
        $form = $this->createForm(ImportType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $jsonFile = $form->get('jsonFile')->getData();
            if ($jsonFile) {
                $jsonData = file_get_contents($jsonFile->getPathname());
                $jsonDataArray = json_decode($jsonData, true);
                $messageBus->dispatch(new ImportProductAndStockDataMessage($jsonDataArray));
                return $this->redirectToRoute('products');
            }
        }

        return $this->render('product/import_product.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/erp/product', name: 'erp_product', methods:['POST'])]
    public function erpProducts(Request $request, MessageBusInterface $messageBus): Response
    {
        $data = json_decode($request->getContent(), true);
        $messageBus->dispatch(new ImportProductAndStockDataMessage($data));
        return new Response('Data received and dispatched successfully', Response::HTTP_OK);
    }

    #[Route('/products', name: 'products')]
    public function products(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }


    #[Route('/erp/inventories', name: 'app_inventories', methods:['POST'])]
    public function inventories(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

}
