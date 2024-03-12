<?php

declare(strict_types=1);

namespace App\Controller\PurchaseProduct;

use App\Controller\PurchaseProduct\Request\PurchaseProductRequest;
use App\Model\PurchaseProduct\PurchaseProductContextBuilder;
use App\Service\PurchaseProduct\Exception\PurchaseProductException;
use App\Service\PurchaseProduct\PurchaseProductService;
use InvalidArgumentException;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Response(response: 200, description: 'Raw message')]
#[OA\Response(response: 400, description: 'Unprocessable Content')]
class PurchaseProductController extends AbstractController
{
    #[OA\Tag(name: 'purchase')]
    #[Route('/purchase', methods: ['POST'])]
    public function __invoke(
        PurchaseProductRequest $request,
        PurchaseProductContextBuilder $purchaseProductContextBuilder,
        PurchaseProductService $service,
    ): JsonResponse {
        try {
            $context = $purchaseProductContextBuilder->create(
                productId:  $request->product,
                taxNumber:  $request->taxNumber,
                couponCode: $request->couponCode,
            );

            $service->process(
                paymentSystem: $request->paymentProcessor,
                context:       $context
            );
        } catch (InvalidArgumentException | PurchaseProductException  $e) {
            return new JsonResponse(
                data:   $e->getMessage(),
                status: Response::HTTP_BAD_REQUEST,
            );
        }

        return new JsonResponse(
            data:   'Purchased',
            status: Response::HTTP_OK,
        );
    }
}
