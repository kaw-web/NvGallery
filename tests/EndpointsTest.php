<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class EndpointsTest extends WebTestCase
{

    /**
     * @dataProvider getJsonData
     */
    public function testErpProductsEndpoint($jsonData)
    {
        // Créer un client pour interagir avec votre application
        $client = static::createClient();

        // Envoyer une requête POST à l'endpoint 'erp_product'
        $client->request('POST', '/erp/product', [], [], ['CONTENT_TYPE' => 'application/json'], $jsonData);

        // Vérifier si la réponse est réussie (statut HTTP 200)
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        // Vérifier si la réponse contient le message de réussite
        $this->assertStringContainsString('Data received and dispatched successfully', $client->getResponse()->getContent());
    }

    public function getJsonData():iterable
    {
        Yield[
            '{
            "reference": "ELSLADLBR12CA0UI",
            "name": "Ixbaxrii",
            "dropshipping": "false",
            "category": null,
            "description": null,
            "pricing": [
                {
                    "channel": "fr",
                    "vat_rate": 20,
                    "price": 3818
                }
            ]
        }'
        ];
    }
}
