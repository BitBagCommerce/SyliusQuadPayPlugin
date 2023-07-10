# [![](https://bitbag.io/wp-content/uploads/2021/08/QuadPay.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_quad_pay)

# BitBag SyliusQuadPayPlugin

----

[ ![](https://img.shields.io/packagist/l/bitbag/quadpay-plugin.svg) ](https://packagist.org/packages/bitbag/quadpay-plugin "License") 
[ ![](https://img.shields.io/packagist/v/bitbag/quadpay-plugin.svg) ](https://packagist.org/packages/bitbag/quadpay-plugin "Version") 
[ ![](https://img.shields.io/github/actions/workflow/status/BitBagCommerce/SyliusQuadPayPlugin/build.yml?branch=master) ](https://github.com/BitBagCommerce/SyliusQuadPayPlugin/actions?query=branch%3Amaster "Build status")
[ ![](https://img.shields.io/scrutinizer/g/BitBagCommerce/SyliusQuadPayPlugin.svg) ](https://scrutinizer-ci.com/g/BitBagCommerce/SyliusQuadPayPlugin/ "Scrutinizer") 
[ ![](https://poser.pugx.org/bitbag/quadpay-plugin/downloads)](https://packagist.org/packages/bitbag/quadpay-plugin "Total Downloads") 
[ ![Slack](https://img.shields.io/badge/community%20chat-slack-FF1493.svg)](http://sylius-devs.slack.com) 
[ ![Support](https://img.shields.io/badge/support-contact%20author-blue])](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_quad_pay)

At BitBag we do believe in open source. However, we are able to do it just because of our awesome clients, who are kind enough to share some parts of our work with the community. Therefore, if you feel like there is a possibility for us to work  together, feel free to reach out. You will find out more about our professional services, technologies, and contact details at [https://bitbag.io/](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_quad_pay).

Like what we do? Want to join us? Check out our job listings on our [career page](https://bitbag.io/career/?utm_source=github&utm_medium=referral&utm_campaign=career). Not familiar with Symfony & Sylius yet, but still want to start with us? Join our [academy](https://bitbag.io/pl/akademia?utm_source=github&utm_medium=url&utm_campaign=akademia)!

## Table of Content

***

* [Overview](#overview)
* [Support](#we-are-here-to-help)
* [Installation](#installation)
   * [Required merchant configuration](#required-merchant-configuration-in-quadpay)
   * [Cron job](#cron-job)
   * [QuadPay Widget](#quadpay-widget)
   * [Customization](#customization)
   * [Testing](#testing)
* [About us](#about-us)
   * [Community](#community)
* [Demo](#demo-sylius-shop)
* [License](#license)
* [Contact](#contact)

## Overview

This plugin allows you to integrate QuadPay payment with Sylius platform app.

## We are here to help
This **open-source plugin was developed to help the Sylius community**. If you have any additional questions, would like help with installing or configuring the plugin, or need any assistance with your Sylius project - let us know!

[![](https://bitbag.io/wp-content/uploads/2020/10/button-contact.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_quad_pay)

# Installation
----


We work on stable, supported and up-to-date versions of packages. We recommend you to do the same.
```bash
$ composer require bitbag/quadpay-plugin
```

Add plugin dependencies to your `config/bundles.php` file:
```php
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

Add parameters to `config/routes.yaml` file:

```yaml
# config/packages/_sylius.yaml

parameters:
    sylius.form.type.checkout_select_payment.validation_groups: ['sylius', 'checkout_select_payment']
```

Install assets:

```
$ bin/console assets:install
```
Clear cache:

```
$ bin/console cache:clear
```
Add widget to product page: `show.html.twig`
```twig
    {{ bitbag_quadpay_render_widget(product|sylius_resolve_variant|sylius_calculate_price({'channel': sylius.channel}), sylius.channel) }}
```
Create `templates/bundles/SyliusShopBundle/Checkout/SelectPayment/_choice.html.twig`
```twig
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

## Required merchant configuration in QuadPay

Merchant configuration must have `captureFundsOnOrderCreation` set to true.

## Cron job

Integrations should keep track of what orders have been sent to QuadPay for payment and have a scheduled job that runs every 10 minutes or so that checks the status of these orders.

For example:

```bash
*/10 * * * * bin/console bitbag:quadpay:update-payment-state
```

## Testing

```bash
$ composer install
$ cd tests/Application
$ yarn install
$ yarn build
$ bin/console assets:install public -e test
$ bin/console doctrine:schema:create -e test
$ bin/console server:run 127.0.0.1:8080 -d public -e test
$ open http://localhost:8080
$ cd ../..
$ vendor/bin/behat
$ vendor/bin/phpspec run
```


# About us

---

BitBag is a company of people who **love what they do** and do it right. We fulfill the eCommerce technology stack with **Sylius**, Shopware, Akeneo, and Pimcore for PIM, eZ Platform for CMS, and VueStorefront for PWA. Our goal is to provide real digital transformation with an agile solution that scales with the **clients’ needs**. Our main area of expertise includes eCommerce consulting and development for B2C, B2B, and Multi-vendor Marketplaces.</br>
We are advisers in the first place. We start each project with a diagnosis of problems, and an analysis of the needs and **goals** that the client wants to achieve.</br>
We build **unforgettable**, consistent digital customer journeys on top of the **best technologies**. Based on a detailed analysis of the goals and needs of a given organization, we create dedicated systems and applications that let businesses grow.<br>
Our team is fluent in **Polish, English, German and, French**. That is why our cooperation with clients from all over the world is smooth.

**Some numbers from BitBag regarding Sylius:**
- 50+ **experts** including consultants, UI/UX designers, Sylius trained front-end and back-end developers,
- 120+ projects **delivered** on top of Sylius,
- 25+ **countries** of BitBag’s customers,
- 4+ **years** in the Sylius ecosystem.

**Our services:**
- Business audit/Consulting in the field of **strategy** development,
- Data/shop **migration**,
- Headless **eCommerce**,
- Personalized **software** development,
- **Project** maintenance and long term support,
- Technical **support**.

**Key clients:** Mollie, Guave, P24, Folkstar, i-LUNCH, Elvi Project, WestCoast Gifts.

---

If you need some help with Sylius development, don't be hesitated to contact us directly. You can fill the form on [this site](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_quad_pay) or send us an e-mail at hello@bitbag.io!

---

[![](https://bitbag.io/wp-content/uploads/2021/08/sylius-badges-transparent-wide.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_quad_pay)

## Community

---- 

For online communication, we invite you to chat with us & other users on [Sylius Slack](https://sylius-devs.slack.com/).

# Demo Sylius Shop

---

We created a demo app with some useful use-cases of plugins!
Visit [sylius-demo.bitbag.io](https://sylius-demo.bitbag.io/) to take a look at it. The admin can be accessed under
[sylius-demo.bitbag.io/admin/login](https://sylius-demo.bitbag.io/admin/login) link and `bitbag: bitbag` credentials.
Plugins that we have used in the demo:

| BitBag's Plugin | GitHub | Sylius' Store|
| ------ | ------ | ------|
| ACL Plugin | *Private. Available after the purchasing.*| https://plugins.sylius.com/plugin/access-control-layer-plugin/|
| Braintree Plugin | https://github.com/BitBagCommerce/SyliusBraintreePlugin |https://plugins.sylius.com/plugin/braintree-plugin/|
| CMS Plugin | https://github.com/BitBagCommerce/SyliusCmsPlugin | https://plugins.sylius.com/plugin/cmsplugin/|
| Elasticsearch Plugin | https://github.com/BitBagCommerce/SyliusElasticsearchPlugin | https://plugins.sylius.com/plugin/2004/|
| Mailchimp Plugin | https://github.com/BitBagCommerce/SyliusMailChimpPlugin | https://plugins.sylius.com/plugin/mailchimp/ |
| Multisafepay Plugin | https://github.com/BitBagCommerce/SyliusMultiSafepayPlugin |
| Wishlist Plugin | https://github.com/BitBagCommerce/SyliusWishlistPlugin | https://plugins.sylius.com/plugin/wishlist-plugin/|
| **Sylius' Plugin** | **GitHub** | **Sylius' Store** |
| Admin Order Creation Plugin | https://github.com/Sylius/AdminOrderCreationPlugin | https://plugins.sylius.com/plugin/admin-order-creation-plugin/ |
| Invoicing Plugin | https://github.com/Sylius/InvoicingPlugin | https://plugins.sylius.com/plugin/invoicing-plugin/ |
| Refund Plugin | https://github.com/Sylius/RefundPlugin | https://plugins.sylius.com/plugin/refund-plugin/ |

**If you need an overview of Sylius' capabilities, schedule a consultation with our expert.**

[![](https://bitbag.io/wp-content/uploads/2020/10/button_free_consulatation-1.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_quad_pay)

## Additional resources for developers

---
To learn more about our contribution workflow and more, we encourage you to use the following resources:
* [Sylius Documentation](https://docs.sylius.com/en/latest/)
* [Sylius Contribution Guide](https://docs.sylius.com/en/latest/contributing/)
* [Sylius Online Course](https://sylius.com/online-course/)


## License

---

This plugin's source code is completely free and released under the terms of the MIT license.

[//]: # (These are reference links used in the body of this note and get stripped out when the markdown processor does its job. There is no need to format nicely because it shouldn't be seen.)

## Contact

---
If you want to contact us, the best way is to fill the form on [our website](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_quad_pay) or send us an e-mail to hello@bitbag.io with your question(s). We guarantee that we answer as soon as we can!

[![](https://bitbag.io/wp-content/uploads/2021/08/badges-bitbag.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_quad_pay)
