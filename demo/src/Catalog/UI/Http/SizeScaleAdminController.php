<?php

namespace Demo\App\Catalog\UI\Http;

use Demo\App\Catalog\App\HandleCreateSizeScale;
use Demo\App\Catalog\App\HandleDeleteSizeScale;
use Demo\App\Catalog\App\HandleUpdateSizeScale;
use Demo\App\Catalog\Domain\CreateSizeScale;
use Demo\App\Catalog\Domain\DeleteSizeScale;
use Demo\App\Catalog\Domain\SizeScaleRepository;
use Demo\App\Catalog\Domain\UpdateSizeScale;
use Demo\App\Catalog\UI\Component\SizeScale\SizeScaleCreatePage;
use Demo\App\Catalog\UI\Component\SizeScale\SizeScaleEditPage;
use Demo\App\Catalog\UI\Component\SizeScale\SizeScaleListPage;
use Demo\App\Catalog\UI\Component\SizeScale\SizeScaleViewPage;
use Doctrine\Common\Collections\Criteria;
use Krak\Admin\Templates\Crud\CrudListPage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

final class SizeScaleAdminController extends AbstractController
{
    private $sizeScaleRepo;
    private $handleCreateSizeScale;
    private $handleDeleteSizeScale;
    private $handleUpdateSizeScale;

    public function __construct(SizeScaleRepository $sizeScaleRepo, HandleCreateSizeScale $handleCreateSizeScale, HandleDeleteSizeScale $handleDeleteSizeScale, HandleUpdateSizeScale $handleUpdateSizeScale) {
        $this->sizeScaleRepo = $sizeScaleRepo;
        $this->handleCreateSizeScale = $handleCreateSizeScale;
        $this->handleDeleteSizeScale = $handleDeleteSizeScale;
        $this->handleUpdateSizeScale = $handleUpdateSizeScale;
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

    public function editAction(Request $req, string $id) {
        if ($req->isMethod('GET')) {
            $sizeScale = $this->sizeScaleRepo->get($id);
            return new SizeScaleEditPage($sizeScale);
        }

        $res = ($this->handleUpdateSizeScale)(new UpdateSizeScale((int) $id, $req->request->get('name')));
        return $this->redirectToRoute('catalog_size_scale_admin_view', ['id' => $res->id()]);
    }

    public function deleteAction(Request $req, string $id) {
        if (!$this->isCsrfTokenValid('delete-size-scale', $req->request->get('_token'))) {
            throw new InvalidCsrfTokenException();
        }

        ($this->handleDeleteSizeScale)(new DeleteSizeScale($id));

        return $this->redirectToRoute('catalog_size_scale_admin_list');
    }
}
