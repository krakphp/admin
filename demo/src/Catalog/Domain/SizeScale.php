<?php

namespace Demo\App\Catalog\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Krak\Fun\{f, c};

class SizeScale
{
    private $id;
    private $name;
    /** @var SizeScaleStatus */
    private $status;
    /** @var Collection */
    private $sizes;
    /** @var ?SizeScale */
    private $versionRoot;
    /** @var ?SizeScale */
    private $parent;

    public function __construct(string $name, array $sizes) {
        $this->sizes = new ArrayCollection();
        $this->status = SizeScaleStatus::draft();
        $this->update($name, $sizes);
    }

    public function id(): ?int {
        return $this->id;
    }

    public function name(): string {
        return $this->name;
    }

    public function status(): SizeScaleStatus {
        return $this->status;
    }

    public function sizes(): array {
        return $this->sizes->toArray();
    }

    public function publish(): void {
        if (!$this->status->isDraft()) {
            throw new \RuntimeException('Can only publish size scales in draft state.');
        }

        $this->status = SizeScaleStatus::published();
        if ($this->parent) {
            $this->parent->status = SizeScaleStatus::archived();
        }
    }

    public function update(string $name, array $sizes): void {
        if (!$this->status->isDraft()) {
            throw new \RuntimeException('Can only update size scales in draft state.');
        }

        $this->name = $name;


        /** @var SizeScaleSize[] $sizeEntitiesBySize */
        $sizeEntitiesBySize = f\arrayReindex(c\method('size'), $this->sizes);

        // add new sizes
        foreach ($sizes as $size) {
            if (!isset($sizeEntitiesBySize[$size])) {
                $this->sizes->add(new SizeScaleSize($this, $size));
            }
        }
        // remove old sizes
        foreach ($sizeEntitiesBySize as $sizeEntity) {
            if (!in_array($sizeEntity->size(), $sizes)) {
                $this->sizes->removeElement($sizeEntity);
            }
        }
    }
}
