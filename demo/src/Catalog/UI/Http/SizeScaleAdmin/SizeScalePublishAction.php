<?php

namespace Demo\App\Catalog\UI\Http\SizeScaleAdmin;

use Demo\App\Catalog\App\HandlePublishSizeScale;
use Demo\App\Catalog\Domain\PublishSizeScale;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

final class SizeScalePublishAction extends AbstractController
{
    private $publishSizeScale;

    public function __construct(HandlePublishSizeScale $publishSizeScale) {
        $this->publishSizeScale = $publishSizeScale;
    }

    public function __invoke(Request $req, int $id) {
        try {
            ($this->publishSizeScale)(new PublishSizeScale($id));
            $this->addFlash('success', 'Size Scale was successfully published.');
        } catch (\Throwable $e) {
            $this->addFlash('error', 'An error occurred during publishing: ' . $e->getMessage());
        }

        return $this->redirectToRoute('catalog_size_scale_admin_view', ['id' => $id]);
    }
}
