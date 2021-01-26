<?php

namespace Demo\App\Catalog\Infra\Persistence;

use Demo\App\Catalog\Domain\ResultSet;
use Demo\App\Catalog\Domain\SizeScale;
use Demo\App\Catalog\Domain\SizeScaleRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineSizeScaleRepository implements SizeScaleRepository
{
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function get(int $id): SizeScale {
        $res = $this->find($id);
        if (!$res) {
            throw new \RuntimeException('Size Scale not found for id: ' . $id);
        }

        return $res;
    }

    public function find(int $id): ?SizeScale {
        return $this->em->find(SizeScale::class, $id);
    }

    public function search(Criteria $criteria): Collection {
        return $this->em->getRepository(SizeScale::class)->matching($criteria);
    }

    public function save(SizeScale $sizeScale): void {
        $this->em->persist($sizeScale);
        $this->em->flush();
    }

    public function remove(SizeScale $sizeScale): void {
        $this->em->remove($sizeScale);
        $this->em->flush();
    }
}
