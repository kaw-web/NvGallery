<?php

namespace App\MessageHandler;


use App\Message\ImportProductAndStockDataMessage;
use App\Service\ProductImportService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ImportProductMessageHandler
{

    public function __construct(public readonly  ProductImportService $productImportService)
    {
    }

    public function __invoke(ImportProductAndStockDataMessage $message)
   {
      $data = $message->getData();
      $this->productImportService->insertProductStockValues($data);
   }
}