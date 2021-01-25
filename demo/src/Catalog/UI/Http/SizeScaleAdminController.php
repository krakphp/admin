<?php

namespace Demo\App\Catalog\UI\Http;

use Demo\App\Catalog\App\HandleCreateSizeScale;
use Demo\App\Catalog\Domain\CreateSizeScale;
use Demo\App\Catalog\Domain\SizeScaleRepository;
use Demo\App\Catalog\UI\Component\SizeScale\SizeScaleCreatePage;
use Demo\App\Catalog\UI\Component\SizeScale\SizeScaleListPage;
use Demo\App\Catalog\UI\Component\SizeScale\SizeScaleViewPage;
use Doctrine\Common\Collections\Criteria;
use Krak\Admin\Templates\Crud\CrudListPage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

final class SizeScaleAdminController extends AbstractController
{
    private $sizeScaleRepo;
    private $handleCreateSizeScale;

    public function __construct(SizeScaleRepository $sizeScaleRepo, HandleCreateSizeScale $handleCreateSizeScale) {
        $this->sizeScaleRepo = $sizeScaleRepo;
        $this->handleCreateSizeScale = $handleCreateSizeScale;
    }

    public function listAction() {
        return new SizeScaleListPage($this->sizeScaleRepo->search(new Criteria()));
    }

    public function viewAction($id) {
        return new SizeScaleViewPage($this->sizeScaleRepo->find($id));
    }

    public function createAction(Request $req) {
        if ($req->isMethod('GET')) {
            return new SizeScaleCreatePage();
        }

        $res = ($this->handleCreateSizeScale)(new CreateSizeScale($req->request->get('name')));
        return $this->redirectToRoute('catalog_size_scale_admin_view', ['id' => $res->id()]);
    }

    public function deleteAction($id) {
        // TODO
    }
}
