<?php

namespace Demo\App\Catalog\UI\Http;

use Demo\App\Catalog\Domain\SizeScaleRepository;
use Demo\App\Catalog\UI\Component\SizeScale\SizeScaleListPage;
use Demo\App\Catalog\UI\Component\SizeScale\SizeScaleViewPage;
use Doctrine\Common\Collections\Criteria;
use Krak\Admin\Templates\Crud\CrudListPage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SizeScaleAdminController extends AbstractController
{
    private $sizeScaleRepo;

    public function __construct(SizeScaleRepository $sizeScaleRepo) {
        $this->sizeScaleRepo = $sizeScaleRepo;
    }

    public function listAction() {
        return new SizeScaleListPage($this->sizeScaleRepo->search(new Criteria()));
    }

    public function viewAction($id) {
        return new SizeScaleViewPage($this->sizeScaleRepo->find($id));
    }
}
