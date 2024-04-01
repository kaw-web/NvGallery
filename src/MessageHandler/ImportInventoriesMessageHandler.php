<?php

namespace App\MessageHandler;


use App\Message\ImportInventoriesMessage;
use App\Service\ProductImportService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ImportInventoriesMessageHandler
{

    public function __construct(public readonly  ProductImportService $productImportService)
    {
    }

    public function __invoke(ImportInventoriesMessage $message)
   {
      $data = $message->getData();
      $this->productImportService->insertInventoriesValues($data);
   }
}