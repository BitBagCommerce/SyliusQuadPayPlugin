<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusQuadPayPlugin\Validator\Constraints;

use BitBag\SyliusQuadPayPlugin\QuadPayGatewayFactory;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;

final class OrderAmountValidator extends ConstraintValidator
{
    /**
     * @param PaymentInterface $payment
     * @param Constraint|OrderAmount $constraint
     *
     * @inheritdoc
     */
    public function validate($payment, Constraint $constraint): void
    {
        Assert::isInstanceOf($payment, PaymentInterface::class);

        Assert::isInstanceOf($constraint, OrderAmount::class);

        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $payment->getMethod();

        if (null === $paymentMethod) {
            return;
        }

        $gatewayConfig = $paymentMethod->getGatewayConfig();

        if (null === $gatewayConfig || (QuadPayGatewayFactory::FACTORY_NAME !== $gatewayConfig->getFactoryName())) {
            return;
        }

        $config = $gatewayConfig->getConfig();

        $minimumAmount = $config['minimumAmount'];
        $maximumAmount = $config['maximumAmount'];

        if ($minimumAmount > $payment->getAmount()) {
            $this->context->buildViolation($constraint->minimumAmountMessage, [
                '{{ minimumAmount }}' => number_format(abs($minimumAmount / 100), 2, '.', ','),
            ])->atPath('method')->addViolation();
        }

        if ($maximumAmount < $payment->getAmount()) {
            $this->context->buildViolation($constraint->maximumAmountMessage, [
                '{{ maximumAmount }}' => number_format(abs($maximumAmount / 100), 2, '.', ','),
            ])->atPath('method')->addViolation();
        }
    }
}
