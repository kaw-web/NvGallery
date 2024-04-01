<?php

namespace App\Message;

class ImportInventoriesMessage
{

    public function __construct( public array $InventoriesData)
    {
    }

    public function getData()
    {
        return $this->InventoriesData;
    }
}