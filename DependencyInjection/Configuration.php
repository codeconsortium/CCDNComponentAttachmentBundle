<?php

/*
 * This file is part of the CCDN AttachmentBundle
 *
 * (c) CCDN (c) CodeConsortium <http://www.codeconsortium.com/> 
 * 
 * Available on github <http://www.github.com/codeconsortium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CCDNComponent\AttachmentBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;

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
        $rootNode = $treeBuilder->root('attachment');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
		$rootNode
			->children()
				->arrayNode('user')
					->children()
						->scalarNode('profile_route')->defaultValue('cc_profile_show_by_id')->end()
					->end()
				->end()
				->arrayNode('template')
					->children()
						->scalarNode('engine')->defaultValue('twig')->end()
						->scalarNode('theme')->defaultValue('CCDNForumForumBundle:Form:fields.html.twig')->end()
					->end()
				->end()
				->arrayNode('store')
					->children()
						->scalarNode('dir')->end()
					->end()
				->end()
			->end();
		
		$this->addQuotaSection($rootNode);
		
        return $treeBuilder;
    }



	/**
	 *
	 * @access protected
	 * @param ArrayNodeDefinition $node
	 */
	protected function addQuotaSection(ArrayNodeDefinition $node)
	{
		$node
			->children()
				->arrayNode('quota_per_user')
					->children()
						->scalarNode('max_files_quantity')->defaultValue('20')->end()
						->scalarNode('max_filesize_per_file')->defaultValue('200')->end()
						->scalarNode('max_total_quota')->defaultValue('1000')->end()
					->end()
				->end()
			->end();
	}
	
}
