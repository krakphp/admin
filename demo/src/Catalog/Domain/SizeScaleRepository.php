<?php

namespace Demo\App\Catalog\Domain;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;

interface SizeScaleRepository
{
    public function get(int $id): SizeScale;
    public function find(int $id): ?SizeScale;
    /** @return SizeScale[] */
    public function search(Criteria $criteria): Collection;
    public function save(SizeScale $sizeScale): void;
    public function remove(SizeScale $sizeScale): void;
}
