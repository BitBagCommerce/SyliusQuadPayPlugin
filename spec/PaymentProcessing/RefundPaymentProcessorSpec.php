<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusQuadPayPlugin\PaymentProcessing;

use BitBag\SyliusQuadPayPlugin\Client\QuadPayApiClientInterface;
use BitBag\SyliusQuadPayPlugin\PaymentProcessing\PaymentProcessorInterface;
use BitBag\SyliusQuadPayPlugin\PaymentProcessing\RefundPaymentProcessor;
use BitBag\SyliusQuadPayPlugin\QuadPayGatewayFactory;
use Payum\Core\Model\GatewayConfigInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Symfony\Component\HttpFoundation\Session\Session;

final class RefundPaymentProcessorSpec extends ObjectBehavior
{
    function let(Session $session, QuadPayApiClientInterface $quadPayApiClient): void
    {
        $this->beConstructedWith($session, $quadPayApiClient);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(RefundPaymentProcessor::class);
    }

    function it_implements_payment_processor_interface(): void
    {
        $this->shouldHaveType(PaymentProcessorInterface::class);
    }

    function it_processes(
        PaymentInterface $payment,
        PaymentMethodInterface $paymentMethod,
        GatewayConfigInterface $gatewayConfig,
        QuadPayApiClientInterface $quadPayApiClient
    ): void {
        $gatewayConfig->getFactoryName()->willReturn(QuadPayGatewayFactory::FACTORY_NAME);
        $gatewayConfig->getConfig()->willReturn([
            'clientId' => 'test',
            'clientSecret' => 'test',
            'apiEndpoint' => 'https://api-ci.quadpay.com/',
            'authTokenEndpoint' => 'https://api-ci.quadpay.com/',
            'apiAudience' => 'https://api-ci.quadpay.com/',
        ]);
        $paymentMethod->getGatewayConfig()->willReturn($gatewayConfig);
        $payment->getMethod()->willReturn($paymentMethod);
        $payment->getAmount()->willReturn(222222);
        $payment->getDetails()->willReturn([
            'orderToken' => 'test',
        ]);
        /** @var string|array $argumentAny */
        $argumentAny = Argument::any();
        $quadPayApiClient->setConfig(
            'test',
            'test',
            'https://api-ci.quadpay.com/',
            'https://api-ci.quadpay.com/',
            'https://api-ci.quadpay.com/'
        );
        $quadPayApiClient->refund(2222.22, $argumentAny, 'test', null)->willReturn(['refundId' => 'test']);

        $payment->setDetails($argumentAny)->shouldBeCalled();

        $this->process($payment);
    }
}
