<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusQuadPayPlugin\Action;

use BitBag\SyliusQuadPayPlugin\Action\CaptureAction;
use BitBag\SyliusQuadPayPlugin\Client\QuadPayApiClientInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayInterface;
use Payum\Core\Reply\HttpRedirect;
use Payum\Core\Request\Capture;
use Payum\Core\Security\TokenInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\PaymentInterface;

final class CaptureActionSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(CaptureAction::class);
    }

    function it_implements_action_interface(): void
    {
        $this->shouldHaveType(ActionInterface::class);
    }

    function it_implements_api_aware_interface(): void
    {
        $this->shouldHaveType(ApiAwareInterface::class);
    }

    function it_implements_gateway_aware_interface(): void
    {
        $this->shouldHaveType(GatewayAwareInterface::class);
    }

    function it_executes(
        Capture $request,
        ArrayObject $arrayObject,
        PaymentInterface $payment,
        TokenInterface $token,
        GatewayInterface $gateway,
        QuadPayApiClientInterface $quadPayApiClient
    ): void {
        $this->setGateway($gateway);
        $quadPayApiClient->createOrder([])->willReturn(['token' => 'test', 'redirectUrl' => 'test']);
        $this->setApi($quadPayApiClient);
        $token->getTargetUrl()->willReturn('url');
        $arrayObject->getArrayCopy()->willReturn([]);
        $request->getModel()->willReturn($arrayObject);
        $request->getFirstModel()->willReturn($payment);
        $request->getToken()->willReturn($token);

        $arrayObject->offsetExists('orderToken')->shouldBeCalled();
        $arrayObject->offsetSet('merchant', ['redirectConfirmUrl' => 'url', 'redirectCancelUrl' => 'url?&status=abandoned'])->shouldBeCalled();
        $arrayObject->offsetSet('orderToken', 'test')->shouldBeCalled();
        $arrayObject->offsetSet('orderStatus', 'created')->shouldBeCalled();

        $this
            ->shouldThrow(HttpRedirect::class)
            ->during('execute', [$request])
        ;
    }

    function it_supports_only_capture_request_and_array_access(
        Capture $request,
        \ArrayAccess $arrayAccess
    ): void {
        $request->getModel()->willReturn($arrayAccess);

        $this->supports($request)->shouldReturn(true);
    }
}
