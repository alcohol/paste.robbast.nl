<?php

/*
 * (c) Rob Bast <rob.bast@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Alcohol\Paste;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpKernel\Kernel;

class Application extends Kernel
{
    /** @var string */
    protected $name = 'paste';

    /** @var array */
    public static $environments = ['test', 'dev', 'prod'];

    /**
     * @param string $environment
     * @param bool $debug
     *
     * @throws \RuntimeException
     */
    public function __construct(string $environment, bool $debug)
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

    /**
     * @return array
     */
    public function registerBundles()
    {
        return [
            /* 3rd party bundles */
            new FrameworkBundle(),
            new MonologBundle(),
        ];
    }

    /**
     * @param LoaderInterface $loader
     *
     * @throws \RuntimeException
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $config = sprintf('%s/config/config.%s.yml', $this->rootDir, $this->getEnvironment());

        if (!is_readable($config)) {
            throw new \RuntimeException('Cannot read configuration file: ' . $config);
        }

        $loader->load($config);
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return sprintf(
            '%s/var/%s/cache',
            $this->rootDir,
            $this->environment
        );
    }

    /**
     * @return string
     */
    public function getLogDir()
    {
        return sprintf(
            '%s/var/%s/log',
            $this->rootDir,
            $this->environment
        );
    }

    /**
     * @return string
     */
    public function getRootDir()
    {
        return dirname(__DIR__);
    }
}
