<?php

namespace App\Tests;

use App\Message\ImportProductAndStockDataMessage;
use App\MessageHandler\ImportProductMessageHandler;
use App\Service\ProductImportService;
use PHPUnit\Framework\TestCase;

class ImportProductMessageHandlerTest extends TestCase
{

    /**
     * @dataProvider messageDataProvider
     */
    public function testInvoke(array $messageData)
    {
        $productImportServiceMock = $this->createMock(ProductImportService::class);
        $message = new ImportProductAndStockDataMessage($messageData);

        // Définir l'expectation pour la méthode insertProductStockValues
        $productImportServiceMock->expects($this->once())
            ->method('insertProductStockValues')
            ->with($messageData);

        // Instancier le handler avec le mock du service
        $handler = new ImportProductMessageHandler($productImportServiceMock);

        // Appeler la méthode __invoke du handler avec le message simulé
        $handler($message);
    }

    private function messageDataProvider():iterable
    {
        Yield[
            $messageData = [
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
            ]
        ];
    }
}
