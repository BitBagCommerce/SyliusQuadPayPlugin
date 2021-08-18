<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
