<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusQuadPayPlugin;

use BitBag\SyliusQuadPayPlugin\Client\QuadPayApiClientInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

final class QuadPayGatewayFactory extends GatewayFactory
{
    public const FACTORY_NAME = 'quadpay';

    public const CURRENCIES_AVAILABLE = ['USD'];

    protected function populateConfig(ArrayObject $config): void
    {
        $config->defaults([
            'payum.factory_name' => self::FACTORY_NAME,
            'payum.factory_title' => 'QuadPay',
            'payum.http_client' => '@bitbag_sylius_quadpay_plugin.quadpay_api_client',
        ]);

        if (false === (bool) $config['payum.api']) {
            $config['payum.default_options'] = [
                'clientId' => null,
                'clientSecret' => null,
                'apiEndpoint' => null,
                'authTokenEndpoint' => null,
                'apiAudience' => null,
            ];

            $config->defaults($config['payum.default_options']);

            $config['payum.required_options'] = [
                'clientId',
                'clientSecret',
                'apiEndpoint',
                'authTokenEndpoint',
                'apiAudience',
            ];

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                /** @var QuadPayApiClientInterface $quadPayApiClient */
                $quadPayApiClient = $config['payum.http_client'];

                $quadPayApiClient->setConfig(
                    $config['clientId'],
                    $config['clientSecret'],
                    $config['apiEndpoint'],
                    $config['authTokenEndpoint'],
                    $config['apiAudience'],
                );

                return $quadPayApiClient;
            };
        }
    }
}
