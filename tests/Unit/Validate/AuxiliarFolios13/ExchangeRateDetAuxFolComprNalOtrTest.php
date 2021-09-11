<?php

declare(strict_types=1);

namespace PhpCfdi\CeUtils\Tests\Unit\Validate\AuxiliarFolios13;

use PhpCfdi\CeUtils\Tests\TestCase;
use PhpCfdi\CeUtils\Validate\AuxiliarFolios13\Base\ExchangeRateDetAuxFolComprNalOtr;
use PhpCfdi\CeUtils\Validate\Common\BaseExchangeRate;

final class ExchangeRateDetAuxFolComprNalOtrTest extends TestCase
{
    public function testDefinition(): void
    {
        $validator = ExchangeRateDetAuxFolComprNalOtr::create();
        $this->assertInstanceOf(BaseExchangeRate::class, $validator);
        $this->assertSame('AUXFOL13COMOTRX', $validator->getAssertCode('X'));
        $this->assertSame(['RepAux:DetAuxFol', 'RepAux:ComprNalOtr'], $validator->getPath());
    }
}
