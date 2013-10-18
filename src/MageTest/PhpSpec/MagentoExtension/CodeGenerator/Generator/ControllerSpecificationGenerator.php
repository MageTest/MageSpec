<?php

namespace MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator;

use PhpSpec\Console\IO;
use PhpSpec\CodeGenerator\TemplateRenderer;
use PhpSpec\Util\Filesystem;
use PhpSpec\Locator\ResourceInterface;
use PhpSpec\CodeGenerator\Generator\GeneratorInterface;

class ControllerSpecificationGenerator implements GeneratorInterface
{
    private $io;
    private $templates;
    private $filesystem;

    public function __construct(IO $io, TemplateRenderer $templates, Filesystem $filesystem = null)
    {
        $this->io         = $io;
        $this->templates  = $templates;
        $this->filesystem = $filesystem ?: new Filesystem;
    }

    public function supports(ResourceInterface $resource, $generation, array $data)
    {
        return 'controller_specification' === $generation;
    }

    public function generate(ResourceInterface $resource, array $data = array())
    {
        $filepath = $resource->getSpecFilename();
        if ($this->filesystem->pathExists($filepath)) {
            $message = sprintf('File "%s" already exists. Overwrite?', basename($filepath));
            if (!$this->io->askConfirmation($message, false)) {
                return;
            }

            $this->io->writeln();
        }

        $path = dirname($filepath);
        if (!$this->filesystem->isDirectory($path)) {
            $this->filesystem->makeDirectory($path);
        }

        $values = array(
            '%filepath%'  => $filepath,
            '%name%'      => $resource->getSpecName(),
            '%namespace%' => $resource->getSpecNamespace(),
            '%subject%'   => $resource->getSrcClassname()
        );

        if (!$content = $this->templates->render('controller_specification', $values)) {
            $content = $this->templates->renderString(
                file_get_contents(__FILE__, null, null, __COMPILER_HALT_OFFSET__), $values
            );
        }

        $this->filesystem->putFileContents($filepath, $content);
        $this->io->writeln(sprintf(
            "<info>ControllerSpecification for <value>%s</value> created in <value>'%s'</value>.</info>\n",
            $resource->getSrcClassname(), $filepath
        ));
    }

    public function getPriority()
    {
        return 0;
    }
}
__halt_compiler();<?php

namespace %namespace%;

use PhpSpec\ObjectBehavior;
use MageTest\PhpSpec\MagentoExtension\Specification\ControllerBehavior;
use Prophecy\Argument;

class %name% extends ControllerBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('%subject%');
    }
}
