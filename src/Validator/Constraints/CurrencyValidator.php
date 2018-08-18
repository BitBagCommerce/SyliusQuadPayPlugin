<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
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
     * {@inheritdoc}
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
                $message = isset($constraint->message) ? $constraint->message : null;

                $this->context->buildViolation($message, [
                    '{{ currencies }}' => implode(', ', QuadPayGatewayFactory::CURRENCIES_AVAILABLE),
                ])->atPath('channels')->addViolation();

                return;
            }
        }
    }
}
