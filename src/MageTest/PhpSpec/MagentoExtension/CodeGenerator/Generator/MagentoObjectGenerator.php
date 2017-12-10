<?php

namespace MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator;

use PhpSpec\CodeGenerator\Generator\Generator as GeneratorInterface;
use PhpSpec\CodeGenerator\Generator\PromptingGenerator;
use PhpSpec\Locator\Resource as ResourceInterface;

abstract class MagentoObjectGenerator extends PromptingGenerator implements GeneratorInterface
{
    protected function renderTemplate(ResourceInterface $resource, string $filepath): string
    {
        $values = [
            '%filepath%'        => $filepath,
            '%name%'            => $resource->getName(),
            '%extends%'         => $this->getParentClass(),
            '%namespace%'       => $resource->getSrcNamespace(),
            '%namespace_block%' => '' !== $resource->getSrcNamespace()
                ?  sprintf("\n\nnamespace %s;", $resource->getSrcNamespace())
                : '',
        ];

        if (!$content = $this->getTemplateRenderer()->render($this->getTemplateName(), $values)) {
            $content = $this->getTemplateRenderer()->renderString(
                file_get_contents(__DIR__ . $this->getTemplateFile()),
                $values
            );
        }

        return $content;
    }

    abstract protected function getParentClass(): string;

    abstract protected function getTemplateName(): string;

    protected function getTemplateFile(): string
    {
        return '/templates/generic_class.template';
    }
}
