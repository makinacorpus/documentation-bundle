<?php

declare(strict_types=1);

namespace MakinaCorpus\DocumentationBundle;

use MakinaCorpus\DocumentationBundle\DependencyInjection\DocumentationExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class DocumentationBundle extends Bundle
{
    const DEFAULT_BASE_BLOCK = 'body';
    const DEFAULT_BASE_TEMPLATE = 'base.html.twig';
    const PERMISION_VIEW = 'view';

    /**
     * {@inheritdoc}
     */
    protected function createContainerExtension()
    {
        return new DocumentationExtension();
    }
}
