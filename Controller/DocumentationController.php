<?php

declare(strict_types=1);

namespace MakinaCorpus\DocumentationBundle\Controller;

use MakinaCorpus\DocumentationBundle\DocumentationBundle;
use MakinaCorpus\DocumentationBundle\PageIndex;
use MakinaCorpus\DocumentationBundle\PageRenderer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

final class DocumentationController extends Controller
{
    /**
     * Documentation index
     */
    public function index(PageIndex $index): Response
    {
        $this->denyAccessUnlessGranted(DocumentationBundle::PERMISION_VIEW, $index);

        return $this->render('@Documentation/index.html.twig', [
            'contents' => '<p>Pouet</p>',
            'index' => $index,
        ]);
    }

    /**
     * Embedded documentation index
     */
    public function toc(PageIndex $index, string $path): Response
    {
        $page = $index->get($path);

        return $this->render('@Documentation/index-embed.html.twig', [
            'children' => $index->getChildren($path),
            'index' => $index,
            'page' => $page,
            'path' => $path,
        ]);
    }

    /**
     * Documentation page
     */
    public function page(PageRenderer $renderer, PageIndex $index, string $path): Response
    {
        try {
            $page = $index->get($path);
        } catch (\InvalidArgumentException $e) {
            throw $this->createNotFoundException('Not Found', $e);
        }

        $this->denyAccessUnlessGranted(DocumentationBundle::PERMISION_VIEW, $page);

        return $this->render('@Documentation/page.html.twig', [
            'contents' => $renderer->render($page),
            'index' => $index,
            'page' => $page,
            'path' => $path,
        ]);
    }
}
