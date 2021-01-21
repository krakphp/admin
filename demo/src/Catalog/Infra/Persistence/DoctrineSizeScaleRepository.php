<?php

namespace Demo\App\Catalog\Infra\Persistence;

use Demo\App\Catalog\Domain\SizeScale;
use Demo\App\Catalog\Domain\SizeScaleRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineSizeScaleRepository implements SizeScaleRepository
{
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function find(int $id): ?SizeScale {
        return $this->em->find(SizeScale::class, $id);
    }

    public function search(Criteria $criteria): array {
        return $this->em->getRepository(SizeScale::class)->matching($criteria)->toArray();
    }
}
