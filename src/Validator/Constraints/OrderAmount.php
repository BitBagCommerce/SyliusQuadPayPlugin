<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusQuadPayPlugin\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

final class OrderAmount extends Constraint
{
    /** @var string */
    public $minimumAmountMessage;

    /** @var string */
    public $maximumAmountMessage;

    public function validatedBy(): string
    {
        return 'bitbag_sylius_quadpay_plugin_order_amount';
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
