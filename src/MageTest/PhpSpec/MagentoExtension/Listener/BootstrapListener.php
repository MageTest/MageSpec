<?php

namespace MageTest\PhpSpec\MagentoExtension\Listener;

use MageTest\PhpSpec\MagentoExtension\Autoloader\MageLoader;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BootstrapListener implements EventSubscriberInterface
{
    /**
     * @var array
     */
    private $params;

    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public static function getSubscribedEvents()
    {
        return array('beforeSuite' => array('beforeSuite', 1100));
    }

    public function beforeSuite()
    {
        $suite = $config = isset($this->params['mage_locator']) ? $this->params['mage_locator'] : array('main' => '');
        MageLoader::register(
            isset($suite['src_path']) ? rtrim($suite['src_path'], '/') . DIRECTORY_SEPARATOR : 'src',
            isset($suite['code_pool']) ? $suite['code_pool'] : 'local'
        );
    }
}
