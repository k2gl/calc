<?php

declare(strict_types=1);

namespace App\Controller\CalculateProductPrice;

use App\Controller\CalculateProductPrice\Adapter\ProductPriceCalculatorContextAdapter;
use App\Controller\CalculateProductPrice\Request\CalculateProductPriceRequest;
use App\Service\ProductPriceCalculator\ProductPriceCalculator;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Response(response: 400, description: 'Unprocessable Content')]
class CalculateProductPriceController extends AbstractController
{
    #[OA\Tag(name: 'calculate')]
    #[Route('/calculate-price', methods: ['POST'])]
    public function __invoke(
        CalculateProductPriceRequest $request,
        ProductPriceCalculatorContextAdapter $productPriceCalculatorContextBuilder,
        ProductPriceCalculator $calculator,
    ): JsonResponse {
        $context = $productPriceCalculatorContextBuilder->create($request);

        return new JsonResponse(
            $calculator->getPrice($context),
        );
    }
}
