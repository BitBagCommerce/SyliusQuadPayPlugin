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

We want to impact many unique eCommerce projects and build our brand recognition worldwide, so we are heavily involved in creating open-source solutions, especially for Sylius. We have already created over **35 extensions, which have been downloaded almost 2 million times.**

You can find more information about our eCommerce services and technologies on our website: https://bitbag.io/. We have also created a unique service dedicated to creating plugins: https://bitbag.io/services/sylius-plugin-development. 

Do you like our work? Would you like to join us? Check out the **“Career” tab:** https://bitbag.io/pl/kariera. 


# About Us 
---

BitBag is a software house that implements tailor-made eCommerce platforms with the entire infrastructure—from creating eCommerce platforms to implementing PIM and CMS systems to developing custom eCommerce applications, specialist B2B solutions, and migrations from other platforms.

We actively participate in Sylius's development. We have already completed **over 150 projects**, cooperating with clients worldwide, including smaller enterprises and large international companies. We have completed projects for such important brands as **Mytheresa, Foodspring, Planeta Huerto (Carrefour Group), Albeco, Mollie, and ArtNight.**

We have a 70-person team of experts: business analysts and consultants, eCommerce developers, project managers, and QA testers.

**Our services:**
* B2B and B2C eCommerce platform implementations
* Multi-vendor marketplace platform implementations
* eCommerce migrations
* Sylius plugin development
* Sylius consulting
* Project maintenance and long-term support
* PIM and CMS implementations

**Some numbers from BitBag regarding Sylius:**
* 70 experts on board 
* +150 projects delivered on top of Sylius
* 30 countries of BitBag’s customers
* 7 years in the Sylius ecosystem
* +35 plugins created for Sylius

---
[![](https://bitbag.io/wp-content/uploads/2024/09/badges-sylius.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_elasticsearch) 

---

## Table of Content

***

* [Overview](#overview)
* [Installation](#installation)
* [Testing](#testing)
* [Demo](#demo)
* [Additional resources for developers](#additional-resources-for-developers)
* [License](#license)
* [Contact and support](#contact-and-support))
* [Community](#community)

# Overview

This plugin allows you to integrate QuadPay payment with Sylius platform app.

# Installation
----
We work on stable, supported and up-to-date versions of packages. We recommend you to do the same.
```bash
composer require bitbag/quadpay-plugin
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

```bash
bin/console assets:install
```
Clear cache:

```bash
bin/console cache:clear
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

# Testing

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

# Demo 
---
We created a demo app with some useful use-cases of plugins! Visit http://demo.sylius.com/ to take a look at it.

**If you need an overview of Sylius' capabilities, schedule a consultation with our expert.**

[![](https://bitbag.io/wp-content/uploads/2020/10/button_free_consulatation-1.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_quad_pay)


# Additional resources for developers
---
To learn more about our contribution workflow and more, we encourage you to use the following resources:
* [Sylius Documentation](https://docs.sylius.com/en/latest/)
* [Sylius Contribution Guide](https://docs.sylius.com/en/latest/contributing/)
* [Sylius Online Course](https://sylius.com/online-course/)
* [Blog Sylius Quad Pay Plugin ](https://bitbag.io/blog/interest-free-installments-in-sylius-sylius-quad-pay-plugin) 


# License
---

This plugin's source code is completely free and released under the terms of the MIT license.

[//]: # (These are reference links used in the body of this note and get stripped out when the markdown processor does its job. There is no need to format nicely because it shouldn't be seen.)

# Contact and support 
---
This open-source plugin was developed to help the Sylius community. If you have any additional questions, would like help with installing or configuring the plugin, or need any assistance with your Sylius project - let us know! **Contact us** or send us an **e-mail to hello@bitbag.io** with your question(s).

[![](https://bitbag.io/wp-content/uploads/2020/10/button-contact.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_quad_pay)


# Community
---- 

For online communication, we invite you to chat with us & other users on **[Sylius Slack](https://sylius-devs.slack.com/).**

[![](https://bitbag.io/wp-content/uploads/2024/09/badges-partners.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_quad_pay)
