# Installation

## Overview:
GENERAL
- [Requirements](#requirements)
- [Composer](#composer)
- [Basic configuration](#basic-configuration)
---
FRONTEND
- [Templates](#templates)
---
ADDITIONAL
- [Additional configuration](#additional-configuration)
- [Tests](#tests)
- [Known Issues](#known-issues)
---

## Requirements:
We work on stable, supported and up-to-date versions of packages. We recommend you to do the same.

| Package       | Version         |
|---------------|-----------------|
| PHP           | \>8.0           |
| sylius/sylius | 1.12.x - 1.13.x |
| MySQL         | \>= 5.7         |

## Composer:
```bash
composer require bitbag/quadpay-plugin
```

## Basic configuration:
Add plugin dependencies to your `config/bundles.php` file:

```php
# config/bundles.php

return [
    ...
    BitBag\SyliusQuadPayPlugin\BitBagSyliusQuadPayPlugin::class => ['all' => true],
];
```

Import required config in your `config/packages/_sylius.yaml` file:

```yaml
# config/packages/_sylius.yaml

imports:
    ...
    - { resource: "@BitBagSyliusQuadPayPlugin/Resources/config/config.yml" }
```

Add parameters to `config/packages/_sylius.yaml` file:

```yaml
# config/packages/_sylius.yaml

parameters:
    sylius.form.type.checkout_select_payment.validation_groups: ['sylius', 'checkout_select_payment']
```

### Install assets:

```yaml
bin/console assets:install
```

### Clear application cache by using command:
```bash
bin/console cache:clear
```
**Note:** If you are running it on production, add the `-e prod` flag to this command.

## Templates
Copy required templates into correct directories in your project.

**ShopBundle** (`templates/bundles/SyliusShopBundle`):
```
vendor/sylius/sylius/src/Sylius/Bundle/ShopBundle/Resources/views/Product/show.html.twig
```

Add widget to product page: `show.html.twig`
```php
{{ bitbag_quadpay_render_widget(product|sylius_resolve_variant|sylius_calculate_price({'channel': sylius.channel}), sylius.channel) }}
```

Override `templates/bundles/SyliusShopBundle/Checkout/SelectPayment/_choice.html.twig` template:
```php
{% set qudPayFactoryName = constant('BitBag\\SyliusQuadPayPlugin\\QuadPayGatewayFactory::FACTORY_NAME') %}

{% set is_quad_pay_method = method.gatewayConfig.factoryName is defined and qudPayFactoryName == method.gatewayConfig.factoryName %}


<div class="item" {{ sylius_test_html_attribute('payment-item') }} {{ is_quad_pay_method ? 'quadpay-method' : '' }}>
    <div class="field">
        <div class="ui radio checkbox" {{ sylius_test_html_attribute('payment-method-checkbox') }}>
            {{ form_widget(form, sylius_test_form_attribute('payment-method-select')) }}
        </div>
    </div>
    <div class="content">
        {% if is_quad_pay_method %}
        <a class="header">
            <img src="{{ asset('bundles/bitbagsyliusquadpayplugin/img/quadpay_4interestfree_lc@2x.png') }}">
        </a>
        <div>
            <p>{{ method.description }}</p>
        </div>
        {% else %}
            <a class="header">{{ form_label(form, null, {'label_attr': {'data-test-payment-method-label': ''}}) }}</a>
            {% if method.description is not null %}
                <div class="description">
                    <p>{{ method.description }}</p>
                </div>
            {% endif %}
        {% endif %}
    </div>
</div>
```

## Additional configuration
### Required merchant configuration in QuadPay

Merchant configuration must have `captureFundsOnOrderCreation` set to true.

## Cron job

Integrations should keep track of what orders have been sent to QuadPay for payment and have a scheduled job that runs every 10 minutes or so that checks the status of these orders.

For example:

```bash
*/10 * * * * bin/console bitbag:quadpay:update-payment-state
```

## Tests
To run the tests, execute the commands:
```bash
composer install
cd tests/Application
yarn install
yarn build
bin/console assets:install public -e test
bin/console doctrine:schema:create -e test
bin/console server:run 127.0.0.1:8080 -d public -e test
open http://localhost:8080
cd ../..
vendor/bin/behat
vendor/bin/phpspec run
```

## Known issues
### Translations not displaying correctly
For incorrectly displayed translations, execute the command:
```bash
bin/console cache:clear
```
