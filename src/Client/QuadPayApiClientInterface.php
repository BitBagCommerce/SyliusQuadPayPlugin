<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusQuadPayPlugin\Client;

interface QuadPayApiClientInterface
{
    public const STATUS_CREATED = 'created';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_DECLINED = 'declined';
    public const STATUS_ABANDONED = 'abandoned';

    public function setConfig(
        string $clientId,
        string $clientSecret,
        string $apiEndpoint,
        string $authTokenEndpoint,
        string $apiAudience
    ): void;

    public function getOrderUrl(?string $orderId = null, ?string $orderToken = null): string;

    public function getRefundUrl(string $orderId): string;

    public function getOauthTokenUrl(): string;

    public function createAccessToken(): array;

    public function createOrder(array $data): array;

    public function getOrderByToken(string $orderToken): array;

    public function getOrderById(string $orderId): array;

    public function refund(
        float $amount,
        string $merchantRefundReference,
        string $orderToken,
        ?string $orderId = null
    ): array;
}
