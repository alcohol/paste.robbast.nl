<?php declare(strict_types=1);

/*
 * (c) Rob Bast <rob.bast@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use Dotenv\Dotenv;

/**
 * @var \Composer\Autoload\ClassLoader
 */
$loader = require dirname(__DIR__) . '/vendor/autoload.php';
$dotenv = new Dotenv(dirname(__DIR__));

if (file_exists(sprintf('%s/%s', dirname(__DIR__), '.env'))) {
    $dotenv->load();
}

$dotenv->required([
    'SYMFONY_ENV',
    'SYMFONY_DEBUG',
    'SECRET',
]);

return $loader;
