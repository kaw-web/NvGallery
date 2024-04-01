<?php

namespace App\Message;

class importInventoriesMessage
{

    public function __construct( public array $InventoriesData)
    {
    }

    public function getData()
    {
        return $this->InventoriesData;
    }
}