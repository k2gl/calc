<?php

declare(strict_types=1);

namespace App\Controller\CalculatePrice;

use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Response(response: 400, description: 'Unprocessable Content')]
class CalculatePriceController extends AbstractController
{
    #[OA\Tag(name: 'calculate')]
    #[Route('/calculate-price', methods: ['POST'])]
    public function __invoke(CalculatePriceRequest $request): JsonResponse
    {
        return new JsonResponse($request);
    }
}
