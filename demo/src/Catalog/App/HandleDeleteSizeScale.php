<?php

namespace Demo\App\Catalog\App;

use Demo\App\Catalog\Domain\DeleteSizeScale;
use Demo\App\Catalog\Domain\SizeScaleRepository;
use Doctrine\ORM\EntityManagerInterface;

final class HandleDeleteSizeScale
{
    private $em;
    private $sizeScaleRepository;

    public function __construct(EntityManagerInterface $em, SizeScaleRepository $sizeScaleRepository) {
        $this->em = $em;
        $this->sizeScaleRepository = $sizeScaleRepository;
    }

    public function __invoke(DeleteSizeScale $command): void {
        $sizeScale = $this->sizeScaleRepository->find($command->sizeScaleId());
        if (!$sizeScale) {
            throw new \RuntimeException('Size Scale not found.');
        }

        $this->em->remove($sizeScale);
        $this->em->flush();
    }
}
