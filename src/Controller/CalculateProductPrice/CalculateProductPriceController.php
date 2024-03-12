<?php

declare(strict_types=1);

namespace App\Controller\CalculateProductPrice;

use App\Controller\CalculateProductPrice\Request\CalculateProductPriceRequest;
use App\Model\PurchaseProduct\PurchaseProductContextBuilder;
use App\Service\ProductPriceCalculator\ProductPriceCalculator;
use InvalidArgumentException;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Response(response: 200, description: 'Raw price')]
#[OA\Response(response: 400, description: 'Unprocessable Content')]
class CalculateProductPriceController extends AbstractController
{
    #[OA\Tag(name: 'calculate')]
    #[Route('/calculate-price', methods: ['POST'])]
    public function __invoke(
        CalculateProductPriceRequest $request,
        PurchaseProductContextBuilder $purchaseProductContextBuilder,
        ProductPriceCalculator $calculator,
    ): JsonResponse {
        try {
            $context = $purchaseProductContextBuilder->create(
                productId: $request->product,
                taxNumber: $request->taxNumber,
                couponCode: $request->couponCode,
            );
        } catch (InvalidArgumentException  $e) {
            return new JsonResponse(
                data:   $e->getMessage(),
                status: Response::HTTP_BAD_REQUEST,
            );
        }

        return new JsonResponse(
            data:   $calculator->getPrice($context),
            status: Response::HTTP_OK,
        );
    }
}
