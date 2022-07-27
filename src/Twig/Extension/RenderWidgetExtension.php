<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusQuadPayPlugin\Twig\Extension;

use BitBag\SyliusQuadPayPlugin\QuadPayGatewayFactory;
use BitBag\SyliusQuadPayPlugin\Repository\PaymentMethodRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class RenderWidgetExtension extends AbstractExtension
{
    /** @var PaymentMethodRepositoryInterface */
    private $paymentMethodRepository;

    /** @var Environment */
    private $templating;

    public function __construct(
        PaymentMethodRepositoryInterface $paymentMethodRepository,
        Environment $templating
    ) {
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->templating = $templating;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('bitbag_quadpay_render_widget', [$this, 'renderQuadPayWidget'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function renderQuadPayWidget(
        int $amount,
        ChannelInterface $channel,
        PaymentMethodInterface $paymentMethod = null
    ): string
    {
        if (null === $paymentMethod) {
            $paymentMethod = $this->paymentMethodRepository->findOneByGatewayFactoryNameAndChannel(QuadPayGatewayFactory::FACTORY_NAME, $channel);
        }

        if (null === $paymentMethod) {
            return '';
        }

        $config = $paymentMethod->getGatewayConfig()->getConfig();

        return $this->templating->render('@BitBagSyliusQuadPayPlugin/_widget.html.twig', [
            'amount' => $amount,
            'paymentMethod' => $paymentMethod,
            'minAmount' => $config['minimumAmount'],
            'maxAmount' => $config['maximumAmount'],
        ]);
    }
}
