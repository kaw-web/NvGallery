<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ImportType;
use App\Message\ImportInventoriesMessage;
use App\Message\ImportProductAndStockDataMessage;
use App\MessageHandler\ImportInventoriesMessageHandler;
use App\Service\ImportDisplayProductService;
use App\Service\ProductImportService;
use Knp\Component\Pager\PaginatorInterface;
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
    public function products(Request $request, PaginatorInterface $paginator, ProductImportService $productImportService): Response
    {
        $search= $request->query->get('search');
        $products = $paginator->paginate(
            $productImportService->getProductList($search),
            $request->query->getInt('page',1),
            ProductImportService::ITEMS_PER_Page
        );
        return $this->render('product/list_product.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/product/detail/{id}', name: 'product_detail', methods:['GET'])]
    public function productDetail(Request $request, ProductImportService $productImportService, Product $product): Response
    {
        $stocks = $productImportService->getSTockForProduct($product);
        return $this->render('product/stocks_for_product.html.twig',
            ['stocks' => $stocks,
            'product' => $product,]);
    }

}
