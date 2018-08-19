<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
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
