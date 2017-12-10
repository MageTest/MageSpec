<?php

namespace MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator;

use PhpSpec\CodeGenerator\Generator\PromptingGenerator;
use PhpSpec\CodeGenerator\Generator\Generator as GeneratorInterface;
use PhpSpec\Locator\Resource as ResourceInterface;

class ControllerSpecificationGenerator extends PromptingGenerator implements GeneratorInterface
{
    const SUPPORTED_GENERATOR = 'controller_specification';

    public function supports(ResourceInterface $resource, string $generation, array $data): bool
    {
        return self::SUPPORTED_GENERATOR === $generation;
    }

    public function getPriority(): int
    {
        return 0;
    }

    protected function getFilePath(ResourceInterface $resource): string
    {
        return $resource->getSpecFilename();
    }

    protected function renderTemplate(ResourceInterface $resource, string $filepath): string
    {
        $values = [
            '%filepath%'  => $filepath,
            '%name%'      => $resource->getSpecName(),
            '%namespace%' => $resource->getSpecNamespace(),
            '%subject%'   => $resource->getSrcClassname()
        ];

        if (!$content = $this->getTemplateRenderer()->render(self::SUPPORTED_GENERATOR, $values)) {
            $content = $this->getTemplateRenderer()->renderString(
                file_get_contents(__DIR__ . '/templates/controller_spec.template'),
                $values
            );
        }

        return $content;
    }

    protected function getGeneratedMessage(ResourceInterface $resource, string $filepath): string
    {
        return sprintf(
            "<info>ControllerSpecification for <value>%s</value> created in <value>'%s'</value>.</info>\n",
            $resource->getSrcClassname(),
            $filepath
        );
    }
}
