<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusQuadPayPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use BitBag\SyliusQuadPayPlugin\QuadPayGatewayFactory;
use Doctrine\Persistence\ObjectManager;
use SM\Factory\FactoryInterface as StateMachineFactoryInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Payment\PaymentTransitions;

final class OrderContext implements Context
{
    /** @var ObjectManager */
    private $objectManager;

    /** @var StateMachineFactoryInterface */
    private $stateMachineFactory;

    public function __construct(
        ObjectManager $objectManager,
        StateMachineFactoryInterface $stateMachineFactory
    ) {
        $this->objectManager = $objectManager;
        $this->stateMachineFactory = $stateMachineFactory;
    }

    /**
     * @Given /^(this order) with QuadPay payment is already paid$/
     */
    public function thisOrderWithQuadPayPaymentIsAlreadyPaid(OrderInterface $order): void
    {
        $this->applyQuadPayPaymentTransitionOnOrder($order, PaymentTransitions::TRANSITION_COMPLETE);

        $this->objectManager->flush();
    }

    private function applyQuadPayPaymentTransitionOnOrder(OrderInterface $order, $transition): void
    {
        foreach ($order->getPayments() as $payment) {
            /** @var PaymentMethodInterface $paymentMethod */
            $paymentMethod = $payment->getMethod();

            if (QuadPayGatewayFactory::FACTORY_NAME === $paymentMethod->getGatewayConfig()->getFactoryName()) {
                $model['orderToken'] = 'test';
                $model['orderId'] = 'test';

                $payment->setDetails($model);
            }

            $this->stateMachineFactory->get($payment, PaymentTransitions::GRAPH)->apply($transition);
        }
    }
}
