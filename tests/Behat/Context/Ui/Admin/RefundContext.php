<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusQuadPayPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use BitBag\SyliusQuadPayPlugin\Client\QuadPayApiClientInterface;
use Sylius\Behat\Context\Ui\Admin\ManagingOrdersContext;
use Sylius\Component\Core\Model\OrderInterface;
use Tests\BitBag\SyliusQuadPayPlugin\Behat\Mocker\QuadPayApiMocker;

final class RefundContext implements Context
{
    /** @var QuadPayApiClientInterface */
    private $quadPayApiMocker;

    /** @var ManagingOrdersContext */
    private $managingOrdersContext;

    public function __construct(
        QuadPayApiMocker $quadPayApiMocker,
        ManagingOrdersContext $managingOrdersContext
    ) {
        $this->quadPayApiMocker = $quadPayApiMocker;
        $this->managingOrdersContext = $managingOrdersContext;
    }

    /**
     * @When /^I mark (this order)'s QuadPay payment as refunded$/
     */
    public function iMarkThisOrdersQuadPayPaymentAsRefunded(OrderInterface $order): void
    {
        $this->quadPayApiMocker->mockApiRefundedPayment(function () use ($order) {
            $this->managingOrdersContext->iMarkThisOrderSPaymentAsRefunded($order);
        });
    }
}
