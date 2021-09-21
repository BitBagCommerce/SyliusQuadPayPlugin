<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusQuadPayPlugin\PaymentProcessing;

use BitBag\SyliusQuadPayPlugin\Client\QuadPayApiClientInterface;
use BitBag\SyliusQuadPayPlugin\QuadPayGatewayFactory;
use GuzzleHttp\Exception\ClientException;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Resource\Exception\UpdateHandlingException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

final class RefundPaymentProcessor implements PaymentProcessorInterface
{
    /** @var Session */
    private $session;

    /** @var QuadPayApiClientInterface */
    private $quadPayApiClient;

    /** @var \Faker\Generator */
    private $faker;

    public function __construct(Session $session, QuadPayApiClientInterface $quadPayApiClient)
    {
        $this->session = $session;
        $this->quadPayApiClient = $quadPayApiClient;

        $this->faker = \Faker\Factory::create();
    }

    public function process(PaymentInterface $payment): void
    {
        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $payment->getMethod();

        if (QuadPayGatewayFactory::FACTORY_NAME !== $paymentMethod->getGatewayConfig()->getFactoryName()) {
            return;
        }

        $details = $payment->getDetails();

        if (false === isset($details['orderToken'])) {
            $this->session->getFlashBag()->add('info', 'The payment refund was made only locally.');

            return;
        }

        $gatewayConfig = $paymentMethod->getGatewayConfig()->getConfig();

        $this->quadPayApiClient->setConfig(
            $gatewayConfig['clientId'],
            $gatewayConfig['clientSecret'],
            $gatewayConfig['apiEndpoint'],
            $gatewayConfig['authTokenEndpoint'],
            $gatewayConfig['apiAudience']
        );

        $merchantRefundReference = $this->faker->uuid;

        $details['merchantRefundReference'] = $merchantRefundReference;

        try {
            $result = $this->quadPayApiClient->refund(
                $payment->getAmount() / 100,
                $merchantRefundReference,
                $details['orderToken'],
                $details['orderId'] ?? null
            );

            $details['refundDetails'] = $result;

            $payment->setDetails($details);
        } catch (ClientException $clientException) {
            $message = $clientException->getMessage();

            if (Response::HTTP_UNPROCESSABLE_ENTITY === $clientException->getCode()) {
                $message = (string) $clientException->getResponse()->getBody();
            }

            $this->session->getFlashBag()->add('error', $message);

            throw new UpdateHandlingException();
        }
    }
}
