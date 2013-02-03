<?php

/*
 * This file is part of the CCDNComponent AttachmentBundle
 *
 * (c) CCDN (c) CodeConsortium <http://www.codeconsortium.com/>
 *
 * Available on github <http://www.github.com/codeconsortium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CCDNComponent\AttachmentBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class }
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class Configuration implements ConfigurationInterface
{

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        /** @var ArrayNodeDefinition $rootNode  */
        $rootNode = $treeBuilder->root('ccdn_component_attachment');

        $rootNode
	        ->addDefaultsIfNotSet()
            ->canBeUnset()
            ->children()
                ->arrayNode('user')
			        ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->scalarNode('profile_route')->defaultValue('ccdn_user_profile_show_by_id')->end()
                    ->end()
                ->end()
                ->arrayNode('template')
			        ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->scalarNode('engine')->defaultValue('twig')->end()
                    ->end()
                ->end()
                ->arrayNode('store')
			        ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->scalarNode('dir')->defaultValue('%ccdn_component_attachment_file_store%')->end()
                    ->end()
                ->end()
            ->end();

        $this->addSEOSection($rootNode);
        $this->addQuotaSection($rootNode);
        $this->addAttachmentSection($rootNode);

        return $treeBuilder;
    }

    /**
     *
     * @access protected
     * @param ArrayNodeDefinition $node
     */
    protected function addSEOSection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->canBeUnset()
            ->children()
                ->arrayNode('seo')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->scalarNode('title_length')->defaultValue('67')->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     *
     * @access protected
     * @param ArrayNodeDefinition $node
     */
    protected function addQuotaSection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->canBeUnset()
            ->children()
                ->arrayNode('quota_per_user')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
	                ->children()
                        ->scalarNode('max_files_quantity')->defaultValue('20')->end()
                        ->scalarNode('max_filesize_per_file')->defaultValue('200')->end()
                        ->scalarNode('max_total_quota')->defaultValue('1000')->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     *
     * @access protected
     * @param ArrayNodeDefinition $node
     */
    protected function addAttachmentSection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->canBeUnset()
            ->children()
                ->arrayNode('manage')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('list')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('layout_template')->defaultValue('CCDNComponentCommonBundle:Layout:layout_body_right.html.twig')->end()
                                ->scalarNode('attachments_per_page')->defaultValue('20')->end()
                                ->scalarNode('attachment_uploaded_datetime_format')->defaultValue('d-m-Y - H:i')->end()
                            ->end()
                        ->end()
                        ->arrayNode('upload')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('layout_template')->defaultValue('CCDNComponentCommonBundle:Layout:layout_body_right.html.twig')->end()
                                ->scalarNode('form_theme')->defaultValue('CCDNComponentAttachmentBundle:Form:fields.html.twig')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

}
