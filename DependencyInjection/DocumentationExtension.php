<?php

declare(strict_types=1);

namespace MakinaCorpus\DocumentationBundle\DependencyInjection;

use MakinaCorpus\DocumentationBundle\PageIndex;
use MakinaCorpus\DocumentationBundle\PageRenderer;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class DocumentationExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(\dirname(__DIR__).'/Resources/config'));
        $loader->load('services.yaml');

        $files = [];
        $root = $container->getParameter('kernel.project_dir');
        if ($config['files']) {
            foreach ($config['files'] as $path => $file) {
                $abspath = $root.'/'.$path;
                if (!($file['virtual'] ?? false) && (!\file_exists($abspath) || \is_dir($abspath))) {
                    throw new InvalidArgumentException(\sprintf("File '%s' does not exists", $abspath));
                }
                $files[$path] = $file;
            }
        }

        $container->setDefinition(
            PageIndex::class,
            (new Definition())
                ->setClass(PageIndex::class)
                ->setPublic(false)
                ->setArguments([$root, $files])
        );

        $container
            ->getDefinition(PageRenderer::class)
            ->setArguments([
                (bool)($config['theme']['relocate_orphans'] ?? false),
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        return new DocumentationConfiguration();
    }
}
