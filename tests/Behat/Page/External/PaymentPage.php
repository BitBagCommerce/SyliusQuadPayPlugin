<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusQuadPayPlugin\Behat\Page\External;

use Behat\Mink\Session;
use BitBag\SyliusQuadPayPlugin\Client\QuadPayApiClientInterface;
use FriendsOfBehat\PageObjectExtension\Page\Page;
use Payum\Core\Security\TokenInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class PaymentPage extends Page implements PaymentPageInterface
{

    const TOKEN = 'capture';

    /** @var RepositoryInterface */
    private $securityTokenRepository;

    public function __construct(
        RepositoryInterface $securityTokenRepository,
        Session $session
    ) {
        parent::__construct($session);
        $this->securityTokenRepository = $securityTokenRepository;
    }

    public function capture(): void
    {
        $captureToken = $this->findToken();

        $this->getDriver()->visit($captureToken->getTargetUrl() . '?&' . http_build_query(['status' => QuadPayApiClientInterface::STATUS_ABANDONED]));
    }

    protected function getUrl(array $urlParameters = []): string
    {
        return 'https://checkout.quadpay.com/checkout';
    }

    private function findToken(): TokenInterface
    {
        $tokens = [];

        /** @var TokenInterface $token */
        foreach ($this->securityTokenRepository->findAll() as $token) {
            if (strpos($token->getTargetUrl(), $this::TOKEN)) {
                $tokens[] = $token;
            }
        }

        if (count($tokens) > 0) {
            return end($tokens);
        }

        throw new \RuntimeException('Cannot find capture token, check if you are after proper checkout steps');
    }
}
