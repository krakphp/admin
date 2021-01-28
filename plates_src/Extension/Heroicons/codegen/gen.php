<?php

require_once __DIR__ . '/vendor/autoload.php';

use Krak\Fun\{f, c};
use Laminas\Code\Generator\ClassGenerator;
use Laminas\Code\Generator\FileGenerator;
use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Generator\ParameterGenerator;

final class CommandArgs
{
    private $heroiconsPath;

    public function __construct(string $heroiconsPath) {
        $this->heroiconsPath = $heroiconsPath;
    }

    public function heroiconsPath(): string {
        return $this->heroiconsPath;
    }
}

final class IconFile
{
    private $filePath;
    private $iconName;

    private function __construct(string $filePath, string $iconName) {
        $this->filePath = $filePath;
        $this->iconName = $iconName;
    }

    public static function fromSplFileInfo(SplFileInfo $file): self {
        return new self($file->getPathname(), substr($file->getFilename(), 0, -4));
    }

    public function filePath(): string {
        return $this->filePath;
    }

    public function iconName(): string {
        return $this->iconName;
    }

    public function iconComponentName(): string {
        return str_replace('-', '', ucwords($this->iconName, '-'));
    }

    public function getContent(): string {
        return file_get_contents($this->filePath);
    }
}

function validateArgv($argv): CommandArgs {
    if (count($argv) < 2) {
        printf("usage: %s {heroicons-path}\n", $argv[0]);
        exit(1);
    }

    return new CommandArgs($argv[1]);
}

function filterForFiles() {
    return c\filter(c\method('isFile'));
}

function toIconFiles() {
    return c\map(function(SplFileInfo $file) {
        return IconFile::fromSplFileInfo($file);
    });
}

/** @return IconFile[] */
function listIconFiles(string $path): iterable {
    return f\compose(
        toIconFiles(),
        filterForFiles()
    )(new DirectoryIterator($path));
}

/** @return IconFile[] */
function listSolidIconsPaths(string $path): iterable {
    return listIconFiles($path . '/optimized/solid');
}

/** @return SplFileInfo[] */
function listOutlineIconsPaths(string $path): iterable {
    return listIconFiles($path . '/optimized/outline');
}

function buildIconComponents(string $className, string $namespaceName) {
    /** @param IconFile[] $iconFiles */
    return function(iterable $iconFiles) use ($className, $namespaceName): string {
        $phpFile = FileGenerator::fromArray([
            'namespace' => $namespaceName,
            'Uses' => ['function League\Plates\{p, attrs}'],
            'classes' => [
                ClassGenerator::fromArray([
                    'name' => $className,
                    'flags' => [ClassGenerator::FLAG_ABSTRACT],
                    'methods' => f\arrayMap(function(IconFile $iconFile) {
                        $svg = str_replace('<svg', '<svg <?=attrs(...$attrs)?>', $iconFile->getContent());
                        return MethodGenerator::fromArray([
                            'name' => $iconFile->iconComponentName(),
                            'visibility' => 'public',
                            'static' => true,
                            'parameters' => [ParameterGenerator::fromArray([
                                'name' => 'attrs'
                            ])->setVariadic(true)],
                            'body' => <<<PHP
return p(function() use (\$attrs) {
?> {$svg} <?php
});
PHP
                            ,
                        ]);
                    }, $iconFiles),
                ])
            ],
        ]);

        return $phpFile->generate();
    };
}

function main($argv) {
    $args = validateArgv($argv);

    file_put_contents(
        __DIR__ . '/../SolidIcon.php',
        buildIconComponents('SolidIcon', 'League\\Plates\\Extension\\Heroicons')(
            listSolidIconsPaths($args->heroiconsPath())
        )
    );
    file_put_contents(
        __DIR__ . '/../OutlineIcon.php',
        buildIconComponents('OutlineIcon', 'League\\Plates\\Extension\\Heroicons')(
            listOutlineIconsPaths($args->heroiconsPath())
        )
    );
}

main($argv);
