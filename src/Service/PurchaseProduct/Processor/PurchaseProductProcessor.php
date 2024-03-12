<?php

declare(strict_types=1);

namespace App\Service\PurchaseProduct\Processor;

use App\Model\PurchaseProduct\PurchaseProductContext;
use App\Service\PurchaseProduct\Exception\PurchaseProductException;

interface PurchaseProductProcessor
{
    /** @throws PurchaseProductException */
    public function process(PurchaseProductContext $context): void;
}
