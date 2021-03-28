<?php

declare(strict_types=1);

namespace PhpCfdi\CeUtils;

use CfdiUtils\CadenaOrigen\DOMBuilder;
use CfdiUtils\CadenaOrigen\XsltBuilderPropertyTrait;
use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Nodes\XmlNodeUtils;
use CfdiUtils\XmlResolver\XmlResolver;
use CfdiUtils\XmlResolver\XmlResolverPropertyTrait;
use PhpCfdi\CeUtils\XmlFollowSchemasValidation\XmlFollowSchemasValidation;
use PhpCfdi\CeUtils\XmlFollowSchemasValidation\XmlFollowSchemasValidationException;
use PhpCfdi\Credentials\Credential;

abstract class AbstractCreator
{
    use XsltBuilderPropertyTrait;
    use XmlResolverPropertyTrait;

    public function __construct(?XmlResolver $xmlResolver = null)
    {
        $this->setXsltBuilder(new DOMBuilder());
        $this->setXmlResolver($xmlResolver ?? new XmlResolver());
    }

    abstract protected function getRootNode(): NodeInterface;

    abstract protected function getXsltLocation(): string;

    protected function getSelloAlgorithm(): int
    {
        return OPENSSL_ALGO_SHA1;
    }

    public function addSello(Credential $fiel): self
    {
        $this->getRootNode()->addAttributes([
            'RFC' => $fiel->certificate()->rfc(),
            'noCertificado' => $fiel->certificate()->serialNumber()->decimal(),
            'certificado' => $fiel->certificate()->pemAsOneLine(),
        ]);

        $cadenaDeOrigen = $this->buildCadenaDeOrigen();

        $this->getRootNode()->addAttributes([
            'sello' => base64_encode(
                $fiel->privateKey()->sign($cadenaDeOrigen, $this->getSelloAlgorithm())
            ),
        ]);

        return $this;
    }

    public function buildCadenaDeOrigen(): string
    {
        $location = $this->getXmlResolver()->resolve($this->getXsltLocation(), 'XSLT');
        return $this->getXsltBuilder()->build($this->asXml(), $location);
    }

    public function asXml(): string
    {
        return XmlNodeUtils::nodeToXmlString($this->getRootNode());
    }

    /**
     * @return string[]
     */
    public function validate(): array
    {
        $validator = new XmlFollowSchemasValidation();
        $xmlResolver = $this->hasXmlResolver() ? $this->getXmlResolver() : null;
        try {
            $validator->validate($this->asXml(), $xmlResolver);
        } catch (XmlFollowSchemasValidationException $exception) {
            return $exception->getErrors();
        }
        return [];
    }
}
