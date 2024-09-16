<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusQuadPayPlugin\Form\Type;

use Sylius\Bundle\MoneyBundle\Form\Type\MoneyType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

final class QuadPayGatewayConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('clientId', TextType::class, [
                'label' => 'bitbag_sylius_quadpay_plugin.ui.client_id',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_quadpay_plugin.payment_method.client_id.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('clientSecret', TextType::class, [
                'label' => 'bitbag_sylius_quadpay_plugin.ui.client_secret',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_quadpay_plugin.payment_method.client_secret.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('apiEndpoint', TextType::class, [
                'label' => 'bitbag_sylius_quadpay_plugin.ui.api_endpoint',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_quadpay_plugin.payment_method.api_endpoint.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('authTokenEndpoint', TextType::class, [
                'label' => 'bitbag_sylius_quadpay_plugin.ui.auth_token_endpoint',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_quadpay_plugin.payment_method.auth_token_endpoint.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('apiAudience', TextType::class, [
                'label' => 'bitbag_sylius_quadpay_plugin.ui.api_audience',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_quadpay_plugin.payment_method.api_audience.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('minimumAmount', MoneyType::class, [
                'label' => 'bitbag_sylius_quadpay_plugin.ui.minimum_amount',
                'currency' => 'USD',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_quadpay_plugin.payment_method.minimum_amount.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('maximumAmount', MoneyType::class, [
                'label' => 'bitbag_sylius_quadpay_plugin.ui.maximum_amount',
                'currency' => 'USD',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_quadpay_plugin.payment_method.maximum_amount.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $data = $event->getData();

                $data['payum.http_client'] = '@bitbag_sylius_quadpay_plugin.quadpay_api_client';

                $event->setData($data);
            })
        ;
    }
}
