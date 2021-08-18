<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusQuadPayPlugin\Behat\Mocker;

use BitBag\SyliusQuadPayPlugin\Client\QuadPayApiClientInterface;
use BitBag\SyliusQuadPayPlugin\PaymentProcessing\PaymentProcessorInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use Sylius\Behat\Service\Mocker\MockerInterface;

final class QuadPayApiMocker
{
    /** @var MockerInterface */
    private $mocker;

    public function __construct(MockerInterface $mocker)
    {
        $this->mocker = $mocker;
    }

    public function mockApiCreatePayment(callable $action): void
    {
        $mockService = $this->mocker
            ->mockService('bitbag_sylius_quadpay_plugin.quadpay_api_client', QuadPayApiClientInterface::class)
        ;

        $mockService
            ->shouldReceive('createOrder')
            ->andReturn([
                'redirectUrl' => 'https://checkout.quadpay.com/checkout',
                'token' => 'test',
            ])
        ;

        $mockService->shouldReceive('setConfig');

        $action();

        $this->mocker->unmockService('bitbag_sylius_quadpay_plugin.quadpay_api_client');
    }

    public function mockApiSuccessfulPayment(callable $action): void
    {
        $mockService = $this->mocker
            ->mockService('bitbag_sylius_quadpay_plugin.quadpay_api_client', QuadPayApiClientInterface::class)
        ;

        $mockService
            ->shouldReceive('getOrderByToken', 'getOrderById')
            ->andReturn([
                'orderStatus' => QuadPayApiClientInterface::STATUS_APPROVED,
                'orderId' => 'test',
            ])
        ;

        $mockService->shouldReceive('setConfig');

        $action();

        $this->mocker->unmockService('bitbag_sylius_quadpay_plugin.quadpay_api_client');
    }

    public function mockApiCancelledPayment(callable $action): void
    {
        $mockService = $this->mocker
            ->mockService('bitbag_sylius_quadpay_plugin.quadpay_api_client', QuadPayApiClientInterface::class)
        ;

        $mockService
            ->shouldReceive('getOrderByToken')
            ->andThrow(new ClientException('', new Request('GET', ''), new \GuzzleHttp\Psr7\Response(404)))
        ;

        $mockService->shouldReceive('setConfig');

        $action();

        $this->mocker->unmockService('bitbag_sylius_quadpay_plugin.quadpay_api_client');
    }

    public function mockApiRefundedPayment(callable $action): void
    {
        $mockService = $this->mocker
            ->mockService('bitbag_sylius_quadpay_plugin.payment_processing.refund', PaymentProcessorInterface::class)
        ;

        $mockService
            ->shouldReceive('refund')
            ->andReturn([])
        ;

        $mockService->shouldReceive('process');

        $action();

        $this->mocker->unmockService('bitbag_sylius_quadpay_plugin.payment_processing.refund');
    }
}
