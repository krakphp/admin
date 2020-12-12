<?php

namespace League\Plates\Bridge\Symfony;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

final class SessionFunctions implements ServiceSubscriberInterface
{
    private $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function flashes(): ?FlashBagInterface {
        if (!$this->container->has('session')) {
            throw new \LogicException('You can not use the addFlash method if sessions are disabled. Enable them in "config/packages/framework.yaml".');
        }

        return $this->container->get('session')->getFlashBag();
    }

    public function session(): ?Session {
        if (!$this->container->has('session')) {
            throw new \LogicException('You can not use the addFlash method if sessions are disabled. Enable them in "config/packages/framework.yaml".');
        }

        return $this->container->get('session')->getFlashBag();
    }

    public static function getSubscribedServices()
    {
        return [
            'session' => '?'.SessionInterface::class,
        ];
    }

}
