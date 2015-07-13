<?php

/*
 * (c) Rob Bast <rob.bast@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Alcohol\PasteBundle;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpKernel\Kernel;

class Application extends Kernel
{
    /** @var string */
    protected $name = 'Pastebin';

    /** @var array */
    public static $environments = ['test', 'dev', 'prod'];

    /**
     * @param string $environment
     * @param bool $debug
     * @throws \RuntimeException
     */
    public function __construct($environment, $debug)
    {
        if (!in_array($environment, self::$environments, true)) {
            throw new \RuntimeException(sprintf(
                'Unsupported environment "%s", expected one of: %s',
                $environment,
                implode(', ', self::$environments)
            ));
        }

        parent::__construct($environment, $debug);

        if ($this->isDebug()) {
            Debug::enable();
        }
    }

    public function registerBundles()
    {
        return [
            /* 3rd party bundles */
            new FrameworkBundle(),
            new MonologBundle(),

            /* the application's "bundle" class */
            new PasteBundle(),
        ];
    }

    /**
     * @param LoaderInterface $loader
     * @throws \RuntimeException
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $config = sprintf('%s/config/config.%s.yml', __DIR__, $this->getEnvironment());

        if (!is_readable($config)) {
            throw new \RuntimeException('Cannot read configuration file: ' . $config);
        }

        $loader->load($config);
    }

    /**
     * @return string
     */
    public function getRootDir()
    {
        return dirname(__DIR__);
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return $this->rootDir . '/var/cache/' . $this->environment;
    }

    /**
     * @return string
     */
    public function getLogDir()
    {
        return $this->rootDir . '/var/log/' . $this->environment;
    }
}
