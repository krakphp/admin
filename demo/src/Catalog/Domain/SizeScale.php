<?php

namespace Demo\App\Catalog\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Krak\Fun\{f, c};
use function Krak\Effects\expect;

class SizeScale
{
    private $id;
    private $name;
    /** @var SizeScaleStatus */
    private $status;
    /** @var Collection */
    private $sizes;
    /** @var ?string */
    private $rootVersionId;
    /** @var ?SizeScale */
    private $prevVersion;
    /** @var ?SizeScale */
    private $nextVersion;

    private function __construct(string $name, array $sizes, string $rootVersionId) {
        $this->sizes = new ArrayCollection();
        $this->status = SizeScaleStatus::draft();
        $this->rootVersionId = $rootVersionId;
        $this->update($name, $sizes);
    }

    public static function create(CreateSizeScale $command) {
        $generatedRootVersionId = expect(GeneratedRootVersionId::class, yield new GenerateRootVersionId());
        return new self($command->name(), $command->sizes(), $generatedRootVersionId->rootVersionId());
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

    public function rootVersionId(): ?string {
        return $this->rootVersionId;
    }

    public function sizes(): array {
        return $this->sizes->toArray();
    }

    public function canBeDeleted(): bool {
        return $this->status->isDraft();
    }

    public function canPublish(): bool {
        return $this->status->isDraft();
    }

    public function publish(): void {
        if (!$this->status->isDraft()) {
            throw new \RuntimeException('Can only publish size scales in draft state.');
        }

        $this->status = SizeScaleStatus::published();
        if ($this->prevVersion) {
            $this->prevVersion->status = SizeScaleStatus::archived();
        }
    }

    public function updateFromCommand(UpdateSizeScale $command): void {
        $this->update($command->name(), $command->sizes());
    }

    public function update(string $name, array $sizes): void {
        if ($this->status->isArchived()) {
            throw new \RuntimeException('Cannot update archived size scales.');
        }

        $this->name = $name;

        if ($this->status->isPublished() && $this->sizesAreDifferentThan($sizes)) {
            throw new \RuntimeException('Cannot update sizes for published size scales.');
        }

        /** @var SizeScaleSize[] $sizeEntitiesBySize */
        $sizeEntitiesBySize = f\arrayReindex(c\method('size'), $this->sizes ?? []);

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

    private function sizesAreDifferentThan(array $sizes): bool {
        $existingSizes = f\arrayMap(c\method('size'), $this->sizes);
        sort($sizes);
        sort($existingSizes);
        return $existingSizes !== $sizes;
    }
}

final class GenerateRootVersionId {}
final class GeneratedRootVersionId {
    private $rootVersionId;

    public function __construct(string $rootVersionId) {
        $this->rootVersionId = $rootVersionId;
    }

    public function rootVersionId(): string {
        return $this->rootVersionId;
    }
}
