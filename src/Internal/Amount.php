<?php

declare(strict_types=1);

namespace PhpCfdi\CeUtils\Internal;

use Stringable;

/**
 * Clase para hacer operaciones con tipos t_Importe
 *
 * @internal
 */
final class Amount implements Stringable
{
    public const MIN_VALUE = '-9999999999999999999999.99';

    public const MAX_VALUE = '9999999999999999999999.99';

    private const SCALE = 2;

    private readonly string $value;

    public function __construct(string $value)
    {
        if ('' === $value) {
            $value = '0.00';
        }
        $this->value = $value;
    }

    public static function create(string $value): self
    {
        return new self($value);
    }

    public function add(self $operand): self
    {
        return new self(bcadd($this->value, $operand->value, self::SCALE));
    }

    public function sub(self $operand): self
    {
        return new self(bcsub($this->value, $operand->value, self::SCALE));
    }

    public function equalsTo(self $other): bool
    {
        return 0 === bccomp($this->value, $other->value, self::SCALE);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
