<?php declare(strict_types=1);

/*
 * (c) Rob Bast <rob.bast@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Paste\Controller;

use Paste\Exception\StorageException;
use Paste\Repository\PasteRepository;
use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class ReadController
{
    private PasteRepository $repository;
    private Environment $engine;

    public function __construct(Environment $engine, PasteRepository $repository)
    {
        $this->engine = $engine;
        $this->repository = $repository;
    }

    public function __invoke(Request $request, string $id, bool $raw = false): Response
    {
        try {
            $paste = $this->repository->find($id);
        } catch (StorageException $exception) {
            return new Response(
                sprintf('Paste "%s" not found.', $id),
                Response::HTTP_NOT_FOUND
            );
        }

        $accept = AcceptHeader::fromString($request->headers->get('Accept'));

        if ($accept->has('text/html') && !$raw) {
            $body = $this->engine->render('read.html.twig', ['paste' => $paste]);
            $headers = ['Content-Type' => 'text/html'];
        } else {
            $body = $paste->getBody();
            $headers = ['Content-Type' => 'text/plain'];
        }

        $response = new Response($body, 200, $headers);
        $response
            ->setVary(['Accept', 'Accept-Encoding'])
            ->setEtag(md5($response->getContent()))
            ->setTtl(3600)
            ->setClientTtl(300)
        ;

        if (!$request->isNoCache()) {
            $response->isNotModified($request);
        }

        return $response;
    }
}
