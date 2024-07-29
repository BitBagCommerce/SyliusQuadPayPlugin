<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
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
