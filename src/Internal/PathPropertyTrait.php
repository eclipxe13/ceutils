<?php

declare(strict_types=1);

namespace PhpCfdi\CeUtils\Internal;

trait PathPropertyTrait
{
    /** @var list<string> */
    private array $path;

    /** @return list<string> */
    public function getPath(): array
    {
        return $this->path;
    }
}
