<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusQuadPayPlugin\Behat\Page\Admin\PaymentMethod;

use Sylius\Behat\Page\Admin\Crud\CreatePageInterface as BaseCreatePageInterface;

interface CreatePageInterface extends BaseCreatePageInterface
{
    public function setClientId(string $clientId): void;

    public function setClientSecret(string $clientSecret): void;

    public function setApiEndpoint(string $apiEndpoint): void;

    public function setAuthTokenEndpoint(string $authTokenEndpoint): void;

    public function setApiAudience(string $apiAudience): void;

    public function setMinimumAmount(string $minimumAmount): void;

    public function setMaximumAmount(string $maximumAmount): void;

    public function containsErrorWithMessage(string $message, bool $strict = true): bool;
}
