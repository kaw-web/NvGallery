<?php

namespace App\Tests;

use App\Message\ImportProductAndStockDataMessage;
use App\MessageHandler\ImportProductMessageHandler;
use App\Service\ProductImportService;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Expr\Yield_;
use PHPUnit\Framework\TestCase;

class ImportProductMessageHandlerTest extends TestCase
{

    /**
     * @dataProvider productStockDataProvider
     */
    public function testInsertProductStockValues(array $productStockData, int $expectedPersistCalls)
    {
        // Créer un mock pour l'entityManager
        $entityManagerMock = $this->getMockBuilder(EntityManagerInterface::class)
            ->getMock();

        // Créer un mock pour la classe ProductImportService en injectant l'entityManagerMock
        $productImportService = new ProductImportService($entityManagerMock);

        // Créer un mock pour le produit et vérifier qu'il est persisté
        $entityManagerMock
            ->expects($this->exactly($expectedPersistCalls))
            ->method('persist');

        // Mock flush pour vérifier qu'il est appelé
        $entityManagerMock->expects($this->once())
            ->method('flush');

        // Appeler la méthode à tester
        $productImportService->insertProductStockValues($productStockData);
    }

    /**
     * @dataProvider  productDataProvider
     */
    public function testExtractProductFromJson(array $productStockData)
    {
        // Créer un mock pour l'entityManager
        $entityManagerMock = $this->getMockBuilder(EntityManagerInterface::class)
            ->getMock();

        // Créer une instance de ProductImportService en injectant l'entityManagerMock
        $productImportService = new ProductImportService($entityManagerMock);

        // Appeler la méthode extractProductFromJson à tester via une méthode publique de ProductImportService
        $product = $productImportService->extractProductFromJson($productStockData);

        // Vérifier si l'objet Product retourné a les bonnes valeurs
        $this->assertEquals($productStockData["reference"], $product->getReference());
        $this->assertEquals($productStockData["name"], $product->getName());
        $this->assertEquals($productStockData["description"], $product->getDescription());
        $this->assertEquals($productStockData["category"], $product->getCategory());
        $this->assertEquals($productStockData["dropshipping"], $product->getDropshipping());
    }

    private function productStockDataProvider(): iterable
    {
        yield [
            [
                [
                    "reference" => "ELSLADLBR12CA0UI",
                    "name" => "Ixbaxrii",
                    "dropshipping" => "false",
                    "category" => null,
                    "description" => null,
                    "pricing" => [
                        [
                            "channel" => "fr",
                            "vat_rate" => 20,
                            "price" => 3818
                        ]
                    ]
                ],
                [
                    "reference" => "DHF55JUZII445",
                    "name" => "Autre Produit",
                    "dropshipping" => "true",
                    "category" => "Catégorie",
                    "description" => "Description du produit",
                    "pricing" => [
                        [
                            "channel" => "fr",
                            "vat_rate" => 20,
                            "price" => 2500
                        ],
                        [
                            "channel" => "it",
                            "vat_rate" => 20,
                            "price" => 3000
                        ]
                    ]
                ]
            ],
            5 // 5 fois: 2 produits + 3 stock
        ];
    }

    private function productDataProvider(): iterable
    {
        Yield[ [
            "reference" => "REF123",
            "name" => "Test Product",
            "description" => "Description",
            "category" => "Category",
            "dropshipping" => true
             ]

        ];
    }
}
