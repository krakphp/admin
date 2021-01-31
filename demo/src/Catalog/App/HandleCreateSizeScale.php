<?php

namespace Demo\App\Catalog\App;

use Demo\App\Catalog\Domain\CreateSizeScale;
use Demo\App\Catalog\Domain\GeneratedRootVersionId;
use Demo\App\Catalog\Domain\GenerateRootVersionId;
use Demo\App\Catalog\Domain\SizeScale;
use Demo\App\Catalog\Domain\SizeScaleRepository;
use function Krak\Effects\handleEffects;

final class HandleCreateSizeScale
{
    private $sizeScaleRepo;

    public function __construct(SizeScaleRepository $sizeScaleRepo) {
        $this->sizeScaleRepo = $sizeScaleRepo;
    }

    public function __invoke(CreateSizeScale $command): SizeScale {
        $sizeScale = handleEffects(SizeScale::create($command), [
            GenerateRootVersionId::class => function() {
                return new GeneratedRootVersionId(bin2hex(random_bytes(4)));
            },
        ]);
        $this->sizeScaleRepo->save($sizeScale);
        return $sizeScale;
    }
}
