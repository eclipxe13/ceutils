<?php

declare(strict_types=1);

namespace PhpCfdi\CeUtils\Validate\AuxiliarFolios13;

use PhpCfdi\CeUtils\Validate\MultiValidator;

final class AuxiliarFolios13MultiValidator extends MultiValidator
{
    protected array $validatorClasses = [
        Base\DocumentDefinition::class,
        Base\DocumentFollowSchemas::class,
        Base\Certificate::class,
        Base\NumOrden::class,
        Base\NumTramite::class,
        Base\UniquePolizaNumber::class,
        Base\DifferentRfcDetAuxFolComprNal::class,
        Base\CurrencyDetAuxFolComprNal::class,
        Base\ExchangeRateDetAuxFolComprNal::class,
        Base\DifferentRfcDetAuxFolComprNalOtr::class,
        Base\CurrencyDetAuxFolComprNalOtr::class,
        Base\ExchangeRateDetAuxFolComprNalOtr::class,
        Base\CurrencyDetAuxFolComprExt::class,
        Base\ExchangeRateDetAuxFolComprExt::class,
    ];
}
