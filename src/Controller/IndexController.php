<?php declare(strict_types=1);

/*
 * (c) Rob Bast <rob.bast@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Paste\Controller;

use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class IndexController
{
    private Environment $engine;

    public function __construct(Environment $engine)
    {
        $this->engine = $engine;
    }

    public function __invoke(Request $request): Response
    {
        $accept = AcceptHeader::fromString($request->headers->get('Accept'));

        try {
            if ($accept->has('text/html')) {
                $body = $this->engine->render('index.html.twig');
                $headers = ['Content-Type' => 'text/html'];
            } else {
                $body = $this->engine->render('index.text.twig');
                $headers = ['Content-Type' => 'text/plain'];
            }
        } catch (LoaderError|SyntaxError|RuntimeError $error) {
            return new Response(
                'Internal Server Error',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        $response = new Response($body, 200, $headers);
        $response
            ->setVary(['Accept', 'Accept-Encoding'])
            ->setEtag(md5($response->getContent()))
            ->setTtl(300)
            ->setClientTtl(60)
        ;

        if (!$request->isNoCache()) {
            $response->isNotModified($request);
        }

        return $response;
    }
}
