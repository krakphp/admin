<?php

namespace Demo\App\Catalog\UI\Http;

use Demo\App\Catalog\UI\ListingQueryParams;
use Demo\App\Catalog\App\{HandleCreateSizeScale, HandleDeleteSizeScale, HandleUpdateSizeScale};
use Demo\App\Catalog\Domain\{CreateSizeScale, DeleteSizeScale, SizeScaleRepository, UpdateSizeScale};
use Demo\App\Catalog\UI\Component\{SizeScale\SizeScaleCreatePage, SizeScale\SizeScaleEditPage,
    SizeScale\SizeScaleListPage, SizeScale\SizeScaleViewPage};
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    public function listAction(Request $req) {
        $params = ListingQueryParams::fromRequest($req);
        return new SizeScaleListPage(
            $this->sizeScaleRepo->search($this->criteriaFromListingQueryParams($params)),
            $params
        );
    }

    private function criteriaFromListingQueryParams(ListingQueryParams $params) {
        $criteria = Criteria::create()
            ->setFirstResult($params->pageSize() * ($params->page() - 1))
            ->setMaxResults($params->pageSize())
        ;

        if ($params->sort()) {
            $sortTuple = $params->sortTuple();
            $criteria = $criteria->orderBy([$sortTuple->field() => $sortTuple->dir()]);
        }

        return $params->search()
            ? $criteria
                ->where(Criteria::expr()->contains('name', $params->search()))
                ->orWhere(Criteria::expr()->eq('rootVersionId', $params->search()))
                ->orWhere(Criteria::expr()->eq('status', $params->search()))
            : $criteria;
    }

    public function viewAction($id) {
        return new SizeScaleViewPage($this->sizeScaleRepo->find($id));
    }

    public function createAction(Request $req) {
        if ($req->isMethod('GET')) {
            return new SizeScaleCreatePage();
        }

        $res = ($this->handleCreateSizeScale)(new CreateSizeScale($req->request->get('name'), $req->request->get('sizes')));
        return $this->redirectToRoute('catalog_size_scale_admin_view', ['id' => $res->id()]);
    }

    public function editAction(Request $req, string $id) {
        if ($req->isMethod('GET')) {
            $sizeScale = $this->sizeScaleRepo->get($id);
            return new SizeScaleEditPage($sizeScale);
        }

        try {
            $res = ($this->handleUpdateSizeScale)(new UpdateSizeScale(
                (int) $id,
                $req->request->get('name'),
                $req->request->get('sizes')
            ));
        } catch (\Throwable $e) {
            $this->addFlash('error', 'An error occurred during editing size scale: ' . $e->getMessage());
            return $this->redirectToRoute('catalog_size_scale_admin_edit', ['id' => $id]);
        }

        return $this->redirectToRoute('catalog_size_scale_admin_view', ['id' => $res->id()]);
    }

    public function deleteAction(Request $req, string $id) {
        ($this->handleDeleteSizeScale)(new DeleteSizeScale($id));

        return $this->redirectToRoute('catalog_size_scale_admin_list');
    }
}
