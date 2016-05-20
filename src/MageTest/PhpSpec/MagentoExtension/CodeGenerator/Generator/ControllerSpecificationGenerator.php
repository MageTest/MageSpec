<?php

namespace MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator;

use PhpSpec\CodeGenerator\Generator\PromptingGenerator;
use PhpSpec\CodeGenerator\Generator\Generator as GeneratorInterface;
use PhpSpec\Locator\Resource as ResourceInterface;

class ControllerSpecificationGenerator extends PromptingGenerator implements GeneratorInterface
{
    /**
     * @param ResourceInterface $resource
     * @param string $generation
     * @param array $data
     * @return bool
     */
    public function supports(ResourceInterface $resource, $generation, array $data)
    {
        return 'controller_specification' === $generation;
    }

    public function getPriority()
    {
        return 0;
    }

    /**
     * @param ResourceInterface $resource
     *
     * @return string
     */
    protected function getFilePath(ResourceInterface $resource)
    {
        return $resource->getSpecFilename();
    }

    /**
     * @param ResourceInterface $resource
     * @param string $filepath
     *
     * @return string
     */
    protected function renderTemplate(ResourceInterface $resource, $filepath)
    {
        $values = array(
            '%filepath%'  => $filepath,
            '%name%'      => $resource->getSpecName(),
            '%namespace%' => $resource->getSpecNamespace(),
            '%subject%'   => $resource->getSrcClassname()
        );

        if (!$content = $this->getTemplateRenderer()->render('controller_specification', $values)) {
            $content = $this->getTemplateRenderer()->renderString(
                file_get_contents(__DIR__ . '/templates/controller_spec.template'), $values
            );
        }

        return $content;
    }

    /**
     * @param ResourceInterface $resource
     * @param string $filepath
     *
     * @return string
     */
    protected function getGeneratedMessage(ResourceInterface $resource, $filepath)
    {
        sprintf(
            "<info>ControllerSpecification for <value>%s</value> created in <value>'%s'</value>.</info>\n",
            $resource->getSrcClassname(),
            $filepath
        );
    }
}
