<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusQuadPayPlugin\PaymentProcessing;

use BitBag\SyliusQuadPayPlugin\Client\QuadPayApiClientInterface;
use BitBag\SyliusQuadPayPlugin\QuadPayGatewayFactory;
use GuzzleHttp\Exception\ClientException;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Resource\Exception\UpdateHandlingException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

final class RefundPaymentProcessor implements PaymentProcessorInterface
{
    /** @var RequestStack */
    private $requestStack;

    /** @var QuadPayApiClientInterface */
    private $quadPayApiClient;

    /** @var \Faker\Generator */
    private $faker;

    public function __construct(RequestStack $requestStack, QuadPayApiClientInterface $quadPayApiClient)
    {
        $this->requestStack = $requestStack;
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
            $this->requestStack->getSession()
                ->getFlashBag()->add('info', 'The payment refund was made only locally.');

            return;
        }

        $gatewayConfig = $paymentMethod->getGatewayConfig()->getConfig();

        $this->quadPayApiClient->setConfig(
            $gatewayConfig['clientId'],
            $gatewayConfig['clientSecret'],
            $gatewayConfig['apiEndpoint'],
            $gatewayConfig['authTokenEndpoint'],
            $gatewayConfig['apiAudience'],
        );

        $merchantRefundReference = $this->faker->uuid;

        $details['merchantRefundReference'] = $merchantRefundReference;

        try {
            $result = $this->quadPayApiClient->refund(
                $payment->getAmount() / 100,
                $merchantRefundReference,
                $details['orderToken'],
                $details['orderId'] ?? null,
            );

            $details['refundDetails'] = $result;

            $payment->setDetails($details);
        } catch (ClientException $clientException) {
            $message = $clientException->getMessage();

            if (Response::HTTP_UNPROCESSABLE_ENTITY === $clientException->getCode()) {
                $message = (string) $clientException->getResponse()->getBody();
            }

            $this->requestStack->getSession()->getFlashBag()->add('error', $message);

            throw new UpdateHandlingException();
        }
    }
}
