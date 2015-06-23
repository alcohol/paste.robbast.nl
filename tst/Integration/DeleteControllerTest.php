<?php

/*
 * (c) Rob Bast <rob.bast@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Alcohol\PasteBundle\Tests\Integration;

use Alcohol\PasteBundle\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @medium
 */
class DeleteControllerTest extends WebTestCase
{
    /**
     * @inheritDoc
     */
    public static function createKernel(array $options = array())
    {
        return new Application(
            isset($options['environment']) ? $options['environment'] : 'test',
            isset($options['debug']) ? $options['debug'] : true
        );
    }

    /**
     * @group functional
     */
    public function testPostRaw()
    {
        $client = static::createClient();
        $client->request('POST', '/', [], [], [], 'Lorem ipsum');
        $token = $client->getResponse()->headers->get('X-Paste-Token');
        $location = $client->getResponse()->headers->get('Location');

        $client->request('DELETE', $location, [], [], ['HTTP_X-Paste-Token' => $token]);

        $this->assertEquals(
            204,
            $client->getResponse()->getStatusCode(),
            '"DELETE /{id}" should return a 204 No Content response.'
        );

        $client->request('GET', $location);

        $this->assertEquals(
            404,
            $client->getResponse()->getStatusCode(),
            '"GET /{id}" should return a 404 Not Found after deleting.'
        );
    }
}
