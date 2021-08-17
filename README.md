# [![](https://bitbag.io/wp-content/uploads/2021/08/QuadPay.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_quad_pay)

# BitBag SyliusQuadPayPlugin
----

<p>
 <img src="https://sylius.com/assets/badge-approved-by-sylius.png" width="85">
</p> 
At BitBag we do believe in open source. However, we are able to do it just because of our awesome clients, who are kind enough to share some parts of our work with the community. Therefore, if you feel like there is a possibility for us working together, feel free to reach us out. You will find out more about our professional services, technologies and contact details at 

[https://bitbag.io/](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_wishlist).

## Table of Content

***

* [Overwiev](#overwiev)
* [Support](#we-are-here-to-help)
* [Installation](#installation)
   * [Usage](#usage)
   * [Customization](#customization)
   * [Testing](#testing)
* [About us](#about-us)
   * [Community](#community)
* [Demo Sylius shop](#demo-sylius-shop)
* [Additional Sylius resources for developers](#additional-resources-for-developers)
* [License](#license)
* [Contact](#contact)

# Overwiev
----
This plugin allows you to integrate wishlist features with Sylius platform app.


## We are here to help
This **open-source plugin was developed to help the Sylius community** and make Mollie payments platform available to any Sylius store. If you have any additional questions, would like help with installing or configuring the plugin or need any assistance with your Sylius project - let us know!

[![](https://bitbag.io/wp-content/uploads/2020/10/button-contact.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_quadpay)


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

```
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

{% set isQuadPayMethod = method.gatewayConfig.factoryName is defined and qudPayFactoryName == method.gatewayConfig.factoryName %}


<div class="item" {{ sylius_test_html_attribute('payment-item') }} {{ isQuadPayMethod ? 'quadpay-method' : '' }}>
    <div class="field">
        <div class="ui radio checkbox" {{ sylius_test_html_attribute('payment-method-checkbox') }}>
            {{ form_widget(form, sylius_test_form_attribute('payment-method-select')) }}
        </div>
    </div>
    <div class="content">
        {% if isQuadPayMethod %}
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
## Testing
----

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

BitBag is an agency that provides high-quality **eCommerce and Digital Experience software**. Our main area of expertise includes eCommerce consulting and development for B2C, B2B, and Multi-vendor Marketplaces.
The scope of our services related to Sylius includes:
- **Consulting** in the field of strategy development
- Personalized **headless software development**
- **System maintenance and long-term support**
- **Outsourcing**
- **Plugin development**
- **Data migration**

Some numbers regarding Sylius:
* **20+ experts** including consultants, UI/UX designers, Sylius trained front-end and back-end developers,
* **100+ projects** delivered on top of Sylius,
* Clients from  **20+ countries**
* **3+ years** in the Sylius ecosystem.

---

If you need some help with Sylius development, don't be hesitate to contact us directly. You can fill the form on [this site](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_wishlist) or send us an e-mail to hello@bitbag.io!

---

[![](https://bitbag.io/wp-content/uploads/2020/10/badges-sylius.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_wishlist)

## Community
----
For online communication, we invite you to chat with us & other users on [Sylius Slack](https://sylius-devs.slack.com/).

# Demo Sylius shop
---

We created a demo app with some useful use-cases of plugins!
Visit b2b.bitbag.shop to take a look at it. The admin can be accessed under b2b.bitbag.shop/admin/login link and sylius: sylius credentials.
Plugins that we have used in the demo:
| BitBag's Plugin | GitHub | Sylius' Store|
| ------ | ------ | ------|
| ACL PLugin | *Private. Available after the purchasing.*| https://plugins.sylius.com/plugin/access-control-layer-plugin/|
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

[![](https://bitbag.io/wp-content/uploads/2020/10/button_free_consulatation-1.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_wishlist)

## Additional resources for developers
---
To learn more about our contribution workflow and more, we encourage ypu to use the following resources:
* [Sylius Documentation](https://docs.sylius.com/en/latest/)
* [Sylius Contribution Guide](https://docs.sylius.com/en/latest/contributing/)
* [Sylius Online Course](https://sylius.com/online-course/)

## License
---

This plugin's source code is completely free and released under the terms of the MIT license.

[//]: # (These are reference links used in the body of this note and get stripped out when the markdown processor does its job. There is no need to format nicely because it shouldn't be seen.)

## Contact
---
If you want to contact us, the best way is to fill the form on [our website](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_wishlist) or send us an e-mail to hello@bitbag.io with your question(s). We guarantee that we answer as soon as we can!

[![](https://bitbag.io/wp-content/uploads/2020/10/footer.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_wishlist)
