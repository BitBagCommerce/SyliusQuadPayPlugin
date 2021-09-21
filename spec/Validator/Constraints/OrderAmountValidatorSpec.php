<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusQuadPayPlugin\Validator\Constraints;

use BitBag\SyliusQuadPayPlugin\QuadPayGatewayFactory;
use BitBag\SyliusQuadPayPlugin\Validator\Constraints\OrderAmount;
use BitBag\SyliusQuadPayPlugin\Validator\Constraints\OrderAmountValidator;
use Payum\Core\Model\GatewayConfigInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class OrderAmountValidatorSpec extends ObjectBehavior
{
    function let(ExecutionContextInterface $executionContextInterface): void
    {
        $this->initialize($executionContextInterface);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(OrderAmountValidator::class);
    }

    function it_extends_constraint_validator_class(): void
    {
        $this->shouldHaveType(ConstraintValidator::class);
    }

    function it_validates(
        PaymentMethodInterface $paymentMethod,
        GatewayConfigInterface $gatewayConfig,
        PaymentInterface $payment
    ): void {
        $orderAmountConstraint = new OrderAmount();
        $gatewayConfig->getFactoryName()->willReturn(QuadPayGatewayFactory::FACTORY_NAME);
        $paymentMethod->getGatewayConfig()->willReturn($gatewayConfig);

        $this->validate($payment, $orderAmountConstraint);
    }
}
