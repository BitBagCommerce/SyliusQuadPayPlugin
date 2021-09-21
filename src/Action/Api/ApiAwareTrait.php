<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusQuadPayPlugin\Action\Api;

use BitBag\SyliusQuadPayPlugin\Client\QuadPayApiClientInterface;
use Payum\Core\Exception\UnsupportedApiException;

trait ApiAwareTrait
{
    /** @var QuadPayApiClientInterface */
    protected $quadPayApiClient;

    public function setApi($quadPayApiClient): void
    {
        if (false === $quadPayApiClient instanceof QuadPayApiClientInterface) {
            throw new UnsupportedApiException('Not supported.Expected an instance of ' . QuadPayApiClientInterface::class);
        }

        $this->quadPayApiClient = $quadPayApiClient;
    }
}
