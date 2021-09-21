<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusQuadPayPlugin\Validator\Constraints;

use BitBag\SyliusQuadPayPlugin\QuadPayGatewayFactory;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;

final class CurrencyValidator extends ConstraintValidator
{
    /**
     * @param PaymentMethodInterface $paymentMethod
     * @param Constraint|Currency $constraint
     *
     * @inheritdoc
     */
    public function validate($paymentMethod, Constraint $constraint): void
    {
        Assert::isInstanceOf($paymentMethod, PaymentMethodInterface::class);

        Assert::isInstanceOf($constraint, Currency::class);

        $gatewayConfig = $paymentMethod->getGatewayConfig();

        if (null === $gatewayConfig || ($gatewayConfig->getFactoryName() !== QuadPayGatewayFactory::FACTORY_NAME)) {
            return;
        }

        /** @var ChannelInterface $channel */
        foreach ($paymentMethod->getChannels() as $channel) {
            if (
                null === $channel->getBaseCurrency() ||
                false === in_array(strtoupper($channel->getBaseCurrency()->getCode()), QuadPayGatewayFactory::CURRENCIES_AVAILABLE)
            ) {
                $message = $constraint->message ?? null;

                $this->context->buildViolation($message, [
                    '{{ currencies }}' => implode(', ', QuadPayGatewayFactory::CURRENCIES_AVAILABLE),
                ])->atPath('channels')->addViolation();

                return;
            }
        }
    }
}
