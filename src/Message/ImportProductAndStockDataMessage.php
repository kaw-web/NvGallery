<?php

namespace App\Message;

class ImportProductAndStockDataMessage
{

    public function __construct( public array $productAndStockData)
    {
    }

    public function getData()
    {
        return $this->productAndStockData;
    }
}