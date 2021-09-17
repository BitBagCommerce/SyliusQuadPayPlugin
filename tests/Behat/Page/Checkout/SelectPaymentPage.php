<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusQuadPayPlugin\Behat\Page\Checkout;

use Behat\Mink\Driver\Selenium2Driver;
use Sylius\Behat\Page\Shop\Checkout\SelectPaymentPage as BaseSelectPaymentPage;
use Sylius\Behat\Page\Shop\Checkout\SelectPaymentPageInterface;

class SelectPaymentPage extends BaseSelectPaymentPage implements SelectPaymentPageInterface
{
    public function selectPaymentMethod($paymentMethod): void
    {
        if ($this->getDriver() instanceof Selenium2Driver) {
            $this->getElement('payment_method_select', ['%payment_method%' => $paymentMethod])->click();

            return;
        }

        $paymentMethodOptionElement = $this->getElement('payment_method_select_value', ['%payment_method%' => strtolower($paymentMethod)]);
        $paymentMethodOptionElement->selectOption($paymentMethodOptionElement->getAttribute('value'));
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'address_step_label' => '.steps a:contains("Address")',
            'checkout_subtotal' => '#sylius-checkout-subtotal',
            'next_step' => '#next-step',
            'order_cannot_be_paid_message' => '#sylius-order-cannot-be-paid',
            'payment_method_option' => '.item:contains("%payment_method%") input',
            'payment_method_select' => '.item:contains("%payment_method%") > .field > .ui.radio.checkbox',
            'payment_method_select_value' => 'input[value="%payment_method%"]',
            'shipping_step_label' => '.steps a:contains("Shipping")',
            'warning_no_payment_methods' => '#sylius-order-cannot-be-paid',
        ]);
    }
}
