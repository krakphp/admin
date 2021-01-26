<?php

namespace League\Plates\Extension\Symfony;

use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class CsrfFunctions
{
    private $csrfTokenManager;

    public function __construct(CsrfTokenManagerInterface $csrfTokenManager) {
        $this->csrfTokenManager = $csrfTokenManager;
    }

    public function csrfToken(string $id): string {
        return $this->csrfTokenManager->getToken($id)->getValue();
    }
}
