<?php

namespace Demo\App\Catalog\UI\Http;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class ValidateCsrfEventSubscriber implements EventSubscriberInterface
{
    private $csrfTokenManager;

    public function __construct(CsrfTokenManagerInterface $csrfTokenManager) {
        $this->csrfTokenManager = $csrfTokenManager;
    }

    public function __invoke(ControllerEvent $event) {
        $req = $event->getRequest();
        if (!$req->attributes->get('validateCsrf')) {
            return;
        }

        $tokenName = $req->attributes->get('csrfTokenName', 'token');

        $csrfToken = $event->getRequest()->get('_token');
        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken($tokenName, $csrfToken))) {
            throw new InvalidCsrfTokenException();
        }
    }

    public static function getSubscribedEvents() {
        return [
            KernelEvents::CONTROLLER => '__invoke'
        ];
    }
}
