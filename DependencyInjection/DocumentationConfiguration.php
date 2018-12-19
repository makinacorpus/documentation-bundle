<?php

declare(strict_types=1);

namespace MakinaCorpus\DocumentationBundle\DependencyInjection;

use MakinaCorpus\DocumentationBundle\DocumentationBundle;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

final class DocumentationConfiguration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('documentation');

        $rootNode
            ->children()
                ->arrayNode('files')
                    ->normalizeKeys(false)
                    ->prototype('array')
                        ->children()
                            ->variableNode('title')
                                ->info("Title displayed in table of contents, and page if delta is greater than 0")
                                ->isRequired()
                            ->end()
                            ->variableNode('path')
                                ->info("Logical path in table of contents")
                                ->isRequired()
                            ->end()
                            ->booleanNode('virtual')
                                ->info("Virtual pages don't have a file, but they have a title and display a table of contents")
                                ->defaultValue(false)
                            ->end()
                            ->booleanNode('skip_title')
                                ->info("Set this to true and H1 will be selected from content instead of being the table of content one")
                                ->defaultValue(false)
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('theme')
                    ->children()
                        ->booleanNode('relocate_orphans')
                            ->info("Relocate orphaned titles to their parent")
                            ->defaultValue(false)
                        ->end()
                        ->scalarNode('base_template')
                            ->info("Base template to extend, can be null if you don't want an empty layout")
                            ->defaultValue(DocumentationBundle::DEFAULT_BASE_TEMPLATE)
                        ->end()
                        ->scalarNode('base_block')
                            ->info("Mandatory if base template is set, ignored otherwise")
                            ->defaultValue(DocumentationBundle::DEFAULT_BASE_BLOCK)
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
