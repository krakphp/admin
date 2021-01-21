<?php

namespace Demo\App\Catalog\Domain;

use Doctrine\Common\Collections\Criteria;

interface SizeScaleRepository
{
    public function find(int $id): ?SizeScale;
    /** @return SizeScale[] */
    public function search(Criteria $criteria): array;
}
