<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusQuadPayPlugin\Validator\Constraints;

use BitBag\SyliusQuadPayPlugin\QuadPayGatewayFactory;
use BitBag\SyliusQuadPayPlugin\Validator\Constraints\Currency;
use BitBag\SyliusQuadPayPlugin\Validator\Constraints\CurrencyValidator;
use Doctrine\Common\Collections\ArrayCollection;
use Payum\Core\Model\GatewayConfigInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class CurrencyValidatorSpec extends ObjectBehavior
{
    function let(ExecutionContextInterface $executionContextInterface): void
    {
        $this->initialize($executionContextInterface);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(CurrencyValidator::class);
    }

    function it_extends_constraint_validator_class(): void
    {
        $this->shouldHaveType(ConstraintValidator::class);
    }

    function it_validates(
        PaymentMethodInterface $paymentMethod,
        GatewayConfigInterface $gatewayConfig
    ): void {
        $currencyConstraint = new Currency();
        $gatewayConfig->getFactoryName()->willReturn(QuadPayGatewayFactory::FACTORY_NAME);
        $paymentMethod->getGatewayConfig()->willReturn($gatewayConfig);
        $paymentMethod->getChannels()->willReturn(new ArrayCollection([]));

        $this->validate($paymentMethod, $currencyConstraint);
    }
}
