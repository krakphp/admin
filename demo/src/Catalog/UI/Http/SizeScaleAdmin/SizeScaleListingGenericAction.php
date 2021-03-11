<?php

namespace Demo\App\Catalog\UI\Http\SizeScaleAdmin;

use Demo\App\Catalog\Domain\SizeScaleRepository;
use Demo\App\Catalog\UI\Component\SizeScale\PresentedSizeScale;
use Demo\App\Catalog\UI\ListingQueryParams;
use Doctrine\Common\Collections\Criteria;
use Krak\Admin\Component\ListingPage;
use Krak\Admin\ListingDefinition;
use Krak\Admin\ListingDefinitionField;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use function Krak\Fun\Curried\method;

final class SizeScaleListingGenericAction extends AbstractController
{
    private $sizeScaleRepository;

    public function __construct(SizeScaleRepository $sizeScaleRepository) {
        $this->sizeScaleRepository = $sizeScaleRepository;
    }

    public function __invoke(Request $req) {
        $params = ListingQueryParams::fromRequest($req);
        return new ListingPage(
            (new ListingDefinition('Size Scales | List', [
                (new ListingDefinitionField('ID', method('id')))
                    ->sortable('id'),
                (new ListingDefinitionField('Name', method('name')))
                    ->sortable('name')
                    ->searchable(),
                (new ListingDefinitionField('Status', method('status')))
                    ->sortable('status')
                    ->searchable(),
                (new ListingDefinitionField('Root Version Id', method('rootVersionId')))
                    ->searchable(),
                (new ListingDefinitionField('Sizes', [PresentedSizeScale::class, 'csvSizes'])),
            ], ListingDefinition::symfonyBuildUrl('catalog_size_scale_admin_list_generic'))),
            $this->sizeScaleRepository->search($this->criteriaFromListingQueryParams($params)),
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
}
