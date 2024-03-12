<?php

declare(strict_types=1);

namespace AppTests\Core;

use RuntimeException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Zenstruck\Foundry\Test\Factories;

use function json_decode;
use function json_encode;
use function sprintf;
use function var_export;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
abstract class ApiControllerTestCase extends WebTestCase
{
    use Factories;

    private KernelBrowser $client;
    private ?Response $response = null;
    /** @var array<array-key, mixed>  */
    private array $responseJsonData = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient(
            [
                'environment' => 'test',
                'debug' => true,
            ]
        );
    }

    /**
     * @param array<string, mixed> $query
     * @param array<array-key, mixed> $json
     */
    protected function sendJsonRequest(
        string $method,
        string $url,
        array $query = [],
        array $json = [],
    ): void {
        $this->response = null;
        $this->responseJsonData = [];

        $serverParameters = [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
        ];

        $this->client->request(
            method : $method,
            uri    : $url . ($query ? '?' . http_build_query($query) : ''),
            server : $serverParameters,
            content: json_encode($json, JSON_THROW_ON_ERROR),
        );

        $this->response = $this->client->getResponse();
    }

    protected function getResponse(): Response
    {
        if (!$this->response) {
            throw new RuntimeException('Response not set yet. Call sendJsonRequest() first.');
        }

        return $this->response;
    }

    protected function getResponseJsonData(?string $key = null): mixed
    {
        if (!$this->responseJsonData) {
            $this->responseJsonData = (array) json_decode(
                json       : (string) $this->getResponse()->getContent(),
                associative: true,
                depth      : 512,
                flags      : JSON_THROW_ON_ERROR,
            );
        }

        if (!is_null($key)) {
            return PropertyAccess::createPropertyAccessor()->getValue(
                objectOrArray: $this->responseJsonData,
                propertyPath : $key
            );
        }

        return $this->responseJsonData;
    }

    protected function assertResponseContainsViolation(string $propertyPath, string $code): void
    {
        /** @var array<array{code: string, message: string, propertyPath: string}> $violations */
        $violations = (array) $this->getResponseJsonData(key: '[violations]');
        $contains = false;

        foreach ($violations as $violation) {
            if ($violation['propertyPath'] === $propertyPath && $violation['code'] === $code) {
                $contains = true;
            }
        }

        static::assertTrue(
            condition: $contains,
            message: sprintf(
                "Response does not contain violation of '%s' with code '%s'\n\nList of response Violations: %s",
                $propertyPath,
                $code,
                var_export(value: $violations, return: true),
            ),
        );
    }
}
