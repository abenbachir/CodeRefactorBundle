<?php

namespace Code\RefactorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('code_refactor');

        $rootNode
            ->fixXmlConfig('extension')
            ->children()
                ->arrayNode('scan')
                    ->children()
                        ->arrayNode('extensions')
                            ->example(array('php','twig','yml','xml','js'))
                            ->beforeNormalization()
                                ->ifString()
                                ->then(function($v) {
                                    return preg_split('/\s*,\s*/', $v);
                                })
                            ->end()
                            ->prototype('scalar')->end()
                        ->end() // -> end extensions
                        ->arrayNode('ignore')
                            ->example(array('/vendor','/web','/app/cache','/app/logs','/bin'))
                            ->beforeNormalization()
                                ->ifString()
                                ->then(function($v) {
                                    return preg_split('/\s*,\s*/', $v);
                                })
                            ->end()
                            ->prototype('scalar')->end()
                        ->end() // -> end ignore
                    ->end()
                ->end()// -> end scan
            ->end();

        return $treeBuilder;
    }
}
