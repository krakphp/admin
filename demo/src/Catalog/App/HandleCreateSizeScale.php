<?php

namespace Demo\App\Catalog\App;

use Demo\App\Catalog\Domain\CreateSizeScale;
use Demo\App\Catalog\Domain\SizeScale;
use Demo\App\Catalog\Domain\SizeScaleRepository;

final class HandleCreateSizeScale
{
    private $sizeScaleRepo;

    public function __construct(SizeScaleRepository $sizeScaleRepo) {
        $this->sizeScaleRepo = $sizeScaleRepo;
    }

    public function __invoke(CreateSizeScale $command): SizeScale {
        $sizeScale = SizeScale::create($command);
        $this->sizeScaleRepo->save($sizeScale);
        return $sizeScale;
    }
}
