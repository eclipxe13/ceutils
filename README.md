# phpcfdi/ceutils

[![Source Code][badge-source]][source]
[![Packagist PHP Version Support][badge-php-version]][php-version]
[![Discord][badge-discord]][discord]
[![Latest Version][badge-release]][release]
[![Software License][badge-license]][license]
[![Build Status][badge-build]][build]
[![Reliability][badge-reliability]][reliability]
[![Maintainability][badge-maintainability]][maintainability]
[![Code Coverage][badge-coverage]][coverage]
[![Violations][badge-violations]][violations]
[![Total Downloads][badge-downloads]][downloads]

> Librería de PHP para trabajar con contabilidad electrónica.

:us: The documentation of this project is in spanish as this is the natural language for the intended audience.

## Acerca de

En México, las personas físicas o morales requieren generar su contabilidad electrónica.

Esta librería permite generar, sellar y validar los XML para contabilidad electrónica 1.3.

## Instalación

Usa [composer](https://getcomposer.org/)

```shell
composer require phpcfdi/ceutils
```

## Ejemplo básico de uso `BalanzaCreator13`

```php
<?php

use PhpCfdi\CeUtils\BalanzaCreator13;
use PhpCfdi\Credentials\Credential;

$creator = new BalanzaCreator13([
    'Mes' => '01',
    'Anio' => '2021',
    'TipoEnvio' => 'N',
    'FechaModBal' => '2015-01-01',
]);

$credential = Credential::openFiles(
    $this->filePath('fake-csd/EKU9003173C9.cer'),
    $this->filePath('fake-csd/EKU9003173C9.key'),
    trim($this->fileContents('fake-csd/EKU9003173C9-password.txt'))
);

$creator->addSello($credential);

$balanza = $creator->balanza();

$balanza->addCuenta([
    'NumCta' => '602.01.01',
    'SaldoIni' => '100.50',
    'Debe' => '40',
    'Haber' => '40',
    'SaldoFin' => '100.50'
]);

$balanza->addCuenta([
    'NumCta' => '602.01.02',
    'SaldoIni' => '200.00',
    'Debe' => '20',
    'Haber' => '20',
    'SaldoFin' => '200.00'
]);

$xml = $creator->asXml();
```

## Ejemplo básico de uso `CatalogoCreator13`

```php
<?php

use PhpCfdi\CeUtils\CatalogoCreator13;
use PhpCfdi\Credentials\Credential;

$creator = new CatalogoCreator13([
    'Mes' => '01',
    'Anio' => '2021',
    'TipoEnvio' => 'N',
    'FechaModBal' => '2015-01-01',
]);

/** @var Credential $credential */

$creator->addSello($credential);

$catalogo = $creator->catalogo();

$catalogo->addCuenta([
    'CodAgrup' => '602',
    'NumCta' => '602.01.01',
    'Desc' => 'Account description',
    'SubCtaDe' => '602.01',
    'Nivel' => '3',
    'Natur' => 'A'
]);

$catalogo->addCuenta([
    'CodAgrup' => '602',
    'NumCta' => '602.01.02',
    'Desc' => 'Account description',
    'SubCtaDe' => '602.01',
    'Nivel' => '3',
    'Natur' => 'A'
]);

$xml = $creator->asXml();
```

## Ejemplo básico de uso `AuxiliarFoliosCreator13`

```php
<?php

use PhpCfdi\CeUtils\AuxiliarFoliosCreator13;
use PhpCfdi\Credentials\Credential;

$creator = new AuxiliarFoliosCreator13([
    'Mes' => '01',
    'Anio' => '2021',
    'TipoSolicitud' => 'AF',
    'NumTramite' => '123456',
]);

/** @var Credential $credential */

$creator->addSello($credential);

$reporteAuxiliarFolios = $creator->repAuxFol();

$detalleAuxiliarFolios = $reporteAuxiliarFolios->addDetalleAux([
    'NumUnIdenPol' => '194756',
    'Fecha' => '2021-03-25'
]);

$detalleAuxiliarFolios->addComprNal([
    'UUID_CFDI' => 'fake uuid',
    'MontoTotal' => '100',
    'RFC' => 'fake rfc',
    'MetPagoAux' => '',
    'Moneda' => 'MXN',
]);

$xml = $creator->asXml();
```

## Ejemplo básico de uso `AuxiliarCuentasCreator13`

```php
<?php

use PhpCfdi\CeUtils\AuxiliarCuentasCreator13;
use PhpCfdi\Credentials\Credential;

$creator = new AuxiliarCuentasCreator13([
    'Mes' => '01',
    'Anio' => '2021',
    'TipoSolicitud' => 'AF',
    'NumTramite' => '123456',
]);

/** @var Credential $crcedential */

$creator->addSello($crcedential);

$auxiliarCuentas = $creator->auxiliarCuentas();

$cuenta = $auxiliarCuentas->addCuenta([
    'NumCta' => '602.01.01',
    'DesCta' => 'descripción',
    'SaldoIni' => '100.00',
    'SaldoFin' => '100.00'
]);

$cuenta->addDetalleAux([
    'Fecha' => '2021-03-25',
    'NumUnIdenPol' => '123456',
    'Concepto' => 'concepto 1',
    'Debe' => '50',
    'Haber' => '0'
]);

$xml = $creator->asXml();
```

## Ejemplo básico de uso `PolizasCreator13`

```php
<?php

use PhpCfdi\CeUtils\PolizasCreator13;
use PhpCfdi\Credentials\Credential;

$creator = new PolizasCreator13([
    'Mes' => '01',
    'Anio' => '2021',
    'TipoSolicitud' => 'AF',
    'NumTramite' => '123456',
]);

/** @var Credential $credential */

$creator->addSello($credential);

$polizas = $creator->polizas();

$poliza = $polizas->addPoliza([
    'NumUnIdenPol' => '123456',
    'Fecha' => '2021-03-31',
    'Concepto' => 'Concepto póliza'
]);

$transaccion = $poliza->addTransaccion([
    'NumCta' => '123',
    'DesCta' => 'Descripción cuenta',
    'Concepto' => 'Concepto transacción',
    'Debe' => '100.00',
    'Haber' => '0.00',
]);

$transaccion->addCompNal([
    'UUID_CFDI' => 'adf9d1d2-574d-4781-8874-a9fb1e79930a',
    'RFC' => 'XAXX010101000',
    'MontoTotal' => '100.00',
    'Moneda' => 'MXN',
]);

$xml = $creator->asXml();
```

## Ejemplo básico de validación

Los objetos creadores tienen oportunidad de validar el documento que están creando.

```php
<?php

use PhpCfdi\CeUtils\BalanzaCreator13;

$creator = new BalanzaCreator13([]);
$asserts = $creator->validate();
if ($asserts->hasErrors()) {
    echo 'No se han encontrado errores', PHP_EOL;
} else {
    echo print_r($asserts->errors(), true), PHP_EOL;
}
```

## Soporte

Puedes obtener soporte abriendo un ticker en Github.

Adicionalmente, esta librería pertenece a la comunidad [PhpCfdi](https://www.phpcfdi.com), así que puedes usar los
mismos canales de comunicación para obtener ayuda de algún miembro de la comunidad.

## Compatibilidad

Esta librería se mantendrá compatible con al menos la versión con
[soporte activo de PHP](https://www.php.net/supported-versions.php) más reciente.

También utilizamos [Versionado Semántico 2.0.0](docs/SEMVER.md) por lo que puedes usar esta librería
sin temor a romper tu aplicación.

## Contribuciones

Las contribuciones con bienvenidas. Por favor lee [CONTRIBUTING][] para más detalles
y recuerda revisar el archivo de tareas pendientes [TODO][] y el archivo [CHANGELOG][].

## Copyright and License

The `phpcfdi/ceutils` library is copyright © [PhpCfdi](https://www.phpcfdi.com)
and licensed for use under the MIT License (MIT). Please see [LICENSE][] for more information.

[contributing]: https://github.com/phpcfdi/ceutils/blob/main/CONTRIBUTING.md
[changelog]: https://github.com/phpcfdi/ceutils/blob/main/docs/CHANGELOG.md
[todo]: https://github.com/phpcfdi/ceutils/blob/main/docs/TODO.md

[source]: https://github.com/phpcfdi/ceutils
[php-version]: https://packagist.org/packages/phpcfdi/ceutils
[discord]: https://discord.gg/aFGYXvX
[release]: https://github.com/phpcfdi/ceutils/releases
[license]: https://github.com/phpcfdi/ceutils/blob/main/LICENSE
[build]: https://github.com/phpcfdi/ceutils/actions/workflows/build.yml?query=branch:main
[reliability]:https://sonarcloud.io/component_measures?id=phpcfdi_ceutils&metric=Reliability
[maintainability]: https://sonarcloud.io/component_measures?id=phpcfdi_ceutils&metric=Maintainability
[coverage]: https://sonarcloud.io/component_measures?id=phpcfdi_ceutils&metric=Coverage
[violations]: https://sonarcloud.io/project/issues?id=phpcfdi_ceutils&resolved=false
[downloads]: https://packagist.org/packages/phpcfdi/ceutils

[badge-source]: http://img.shields.io/badge/source-phpcfdi/ceutils-blue?logo=github
[badge-php-version]: https://img.shields.io/packagist/php-v/phpcfdi/ceutils?logo=php
[badge-discord]: https://img.shields.io/discord/459860554090283019?logo=discord
[badge-release]: https://img.shields.io/github/release/phpcfdi/ceutils?logo=git
[badge-license]: https://img.shields.io/github/license/phpcfdi/ceutils?logo=open-source-initiative
[badge-build]: https://img.shields.io/github/actions/workflow/status/phpcfdi/ceutils/build.yml?branch=main&logo=github-actions
[badge-reliability]: https://sonarcloud.io/api/project_badges/measure?project=phpcfdi_ceutils&metric=reliability_rating
[badge-maintainability]: https://sonarcloud.io/api/project_badges/measure?project=phpcfdi_ceutils&metric=sqale_rating
[badge-coverage]: https://img.shields.io/sonar/coverage/phpcfdi_ceutils/main?logo=sonarcloud&server=https%3A%2F%2Fsonarcloud.io
[badge-violations]: https://img.shields.io/sonar/violations/phpcfdi_ceutils/main?format=long&logo=sonarcloud&server=https%3A%2F%2Fsonarcloud.io
[badge-downloads]: https://img.shields.io/packagist/dt/phpcfdi/ceutils?logo=packagist
