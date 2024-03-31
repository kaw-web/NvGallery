<?php

namespace App\Service;

use App\Entity\Channel;
use App\Entity\Product;
use App\Entity\Stock;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\InvalidArgumentException;
use PHPUnit\Util\Exception;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use function PHPUnit\Framework\throwException;

class ProductImportService
{


    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function insertProductStockValues(array $productStockData)
    {
         $product = $this->extractProductFromJson($productStockData);
         $this->entityManager->persist($product);
         $stockList = ($productStockData["pricing"]);
         array_walk($stockList, function($element)use($product){
            $stockObject = $this->extractStockFromJson($element, $product);
           $this->entityManager->persist($stockObject);
         });

        $this->entityManager->flush();
    }

    private function extractProductFromJson(array $productStockData):Product
    {

        if($productStockData["reference"] == null)
            throw new InvalidArgumentException('Le champs "référence" ne peut pas être vide');
        $existingProduct = $this->entityManager->getRepository(Product::class)->findOneBy(["reference"=>$productStockData["reference"]]);
        $product = $existingProduct !=null?$existingProduct:new Product();
        $product->setReference($productStockData["reference"]);
        $product->setName($productStockData["name"]);
        $product->setDescription($productStockData["description"]);
        $product->setCategory($productStockData["category"]);
        $product->setDropshipping($productStockData["dropshipping"]);
        return $product;
    }

    private function extractStockFromJson(array $stockData, Product $product):Stock
    {
        if($stockData["channel"] == null)
            throw new InvalidArgumentException('Le champs "channel" ne peut pas être vide');
        $stock = null;
        $channel = $this->entityManager->getRepository(Channel::class)->findOneBy(['code'=>$stockData["channel"]]);
        $existingStock = $this->entityManager->getRepository(Stock::class)->findOneBy(["channel"=>$channel, "reference"=>$product]);
       if($existingStock == null)
       {
           $stock = new Stock();
           $stock->setChannel($channel);
           $stock->setReference($product);
       }else{
           $stock = $existingStock ;
       }
       $stock->setVatRate($stockData["vat_rate"]);
       $stock->setPrice($stockData["price"]);
       return $stock;
    }
}