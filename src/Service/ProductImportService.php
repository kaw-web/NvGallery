<?php

namespace App\Service;

use App\Entity\Channel;
use App\Entity\Inbounds;
use App\Entity\Inventories;
use App\Entity\Product;
use App\Entity\Stock;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\InvalidArgumentException;


class ProductImportService
{

    public const  ITEMS_PER_Page = 10;
    public function __construct(private readonly entityManagerInterface $entityManager)
    {
    }

    public function insertProductStockValues(array $productStockData):void
    {
        foreach ($productStockData as $prductData) {
            $product = $this->extractProductFromJson($prductData);
            $this->entityManager->persist($product);
            $stockList = $prductData["pricing"];
            array_walk($stockList, function($element)use($product){
                $stockObject = $this->extractStockFromJson($element, $product);
                $this->entityManager->persist($stockObject);
            });
        }
        $this->entityManager->flush();
    }

    public function insertInventoriesValues(array $inventoriesData):void
    {
        foreach ($inventoriesData as $inventoryData) {
            $inventories = $inventoryData["inventories"];
            array_walk($inventories, function($element) use($inventoryData){
                $inventory = $this->extractInventoryFromJson($element, $inventoryData["reference"]);
                $this->entityManager->persist($inventory);
                $inbounds = $element["inbounds"];
                array_walk($inbounds, function($inbound)use($inventory){
                    $inbound = $this->extractInboundFRomJson($inbound, $inventory);
                    $this->entityManager->persist($inbound);
                });
            });
        }
        $this->entityManager->flush();
    }

    public function getProductList(?string $search)
    {
        $query =  $this->entityManager->getRepository(Product::class)
            ->createQueryBuilder('p');
        if($search != null) {
            $query
                ->Where('p.reference LIKE :search')
                ->orWhere('p.name LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }
        return $query->getQuery();
    }

    /**
     * @param Product $product
     * @return array<Stock>
     */
    public function getSTockForProduct(Product $product):array
    {
        return  $this->entityManager->getRepository(Stock::class)->findBy(['reference' => $product]);
    }
    public function extractProductFromJson(array $productStockData):Product
    {

        if($productStockData["reference"] == null)
            throw new \InvalidArgumentException('Le champs "référence" ne peut pas être vide');
        $existingProduct = $this->entityManager->getRepository(Product::class)->findOneBy(["reference"=>$productStockData["reference"]]);
        $product = $existingProduct !=null?$existingProduct:new Product();
        $product->setReference($productStockData["reference"]);
        $product->setName($productStockData["name"]);
        $product->setDescription($productStockData["description"]);
        $product->setCategory($productStockData["category"]);
        $product->setDropshipping($productStockData["dropshipping"]);
        return $product;
    }

    public function extractStockFromJson(array $stockData, Product $product):Stock
    {
        if($stockData["channel"] == null)
            throw new \InvalidArgumentException('Le champs "channel" ne peut pas être vide');
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

    public function extractInventoryFromJson(array $inventoryData, string $reference):Inventories
    {
        $inventory = new Inventories();
        $reference = $this->entityManager->getRepository(Product::class)->findOneBy(["reference"=>$reference]);
        if($reference == null)
            throw new \InvalidArgumentException("Le champ reference ne peut pas etre nulle");
        $inventory->setReference($reference);
        $inventory->setQuantity($inventoryData["quantity"]);
        $inventory->setChannels($inventoryData["channels"]);
        $inventory->setCreatedAt(new \DateTime());
        return $inventory;
    }

    public function extractInboundFRomJson(array $inboudData, Inventories $inventory):Inbounds
    {
        $inbound = new Inbounds();
        $inbound->setInventory($inventory);
        $inbound->setQuantity($inboudData["quantity"]);
        $inbound->setArrivalDate(new \DateTime($inboudData["arrival_date"]));
        return $inbound;
    }
}