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

		// Class file namespaces.
		$this
			->addEntitySection($rootNode)
			->addRepositorySection($rootNode)
			->addGatewaySection($rootNode)
			->addManagerSection($rootNode)
			->addFormSection($rootNode)
			->addComponentSection($rootNode)
		;
		
		// Configuration stuff.
        $this
			->addSEOSection($rootNode)
	        ->addQuotaSection($rootNode)
	        ->addAttachmentSection($rootNode)
		;

        return $treeBuilder;
    }

    /**
     *
     * @access private
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
	 * @return \CCDNComponent\AttachmentBundle\DependencyInjection\Configuration
     */
    private function addEntitySection(ArrayNodeDefinition $node)
	{
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('entity')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
				        ->arrayNode('folder')
				            ->addDefaultsIfNotSet()
				            ->canBeUnset()
				            ->children()
								->scalarNode('class')->defaultValue('CCDNComponent\AttachmentBundle\Entity\Folder')->end()
							->end()
						->end()
				        ->arrayNode('attachment')
				            ->addDefaultsIfNotSet()
				            ->canBeUnset()
				            ->children()
								->scalarNode('class')->defaultValue('CCDNComponent\AttachmentBundle\Entity\Attachment')->end()
							->end()
						->end()
				        ->arrayNode('registry')
				            ->addDefaultsIfNotSet()
				            ->canBeUnset()
				            ->children()
								->scalarNode('class')->defaultValue('CCDNComponent\AttachmentBundle\Entity\Registry')->end()
							->end()
						->end()
					->end()
				->end()
			->end()
		;
		
		return $this;
	}

    /**
     *
     * @access private
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
	 * @return \CCDNComponent\AttachmentBundle\DependencyInjection\Configuration
     */
    private function addRepositorySection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('repository')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('folder')
		                    ->addDefaultsIfNotSet()
		                    ->canBeUnset()
                            ->children()
								->scalarNode('class')->defaultValue('CCDNComponent\AttachmentBundle\Repository\FolderRepository')->end()							
							->end()
						->end()
	                    ->arrayNode('attachment')
		                    ->addDefaultsIfNotSet()
		                    ->canBeUnset()
	                        ->children()
								->scalarNode('class')->defaultValue('CCDNComponent\AttachmentBundle\Repository\AttachmentRepository')->end()							
							->end()
						->end()
                        ->arrayNode('registry')
		                    ->addDefaultsIfNotSet()
		                    ->canBeUnset()
                            ->children()
								->scalarNode('class')->defaultValue('CCDNComponent\AttachmentBundle\Repository\RegistryRepository')->end()							
							->end()
						->end()
					->end()
				->end()
			->end()
		;
		
		return $this;
	}
		
    /**
     *
     * @access private
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
	 * @return \CCDNComponent\AttachmentBundle\DependencyInjection\Configuration
     */
    private function addGatewaySection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('gateway_bag')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
						->scalarNode('class')->defaultValue('CCDNComponent\AttachmentBundle\Gateway\Bag\GatewayBag')->end()							
					->end()
				->end()
                ->arrayNode('gateway')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('folder')
		                    ->addDefaultsIfNotSet()
		                    ->canBeUnset()
                            ->children()
								->scalarNode('class')->defaultValue('CCDNComponent\AttachmentBundle\Gateway\FolderGateway')->end()							
							->end()
						->end()
                        ->arrayNode('attachment')
		                    ->addDefaultsIfNotSet()
		                    ->canBeUnset()
                            ->children()
								->scalarNode('class')->defaultValue('CCDNComponent\AttachmentBundle\Gateway\AttachmentGateway')->end()							
							->end()
						->end()
                        ->arrayNode('registry')
		                    ->addDefaultsIfNotSet()
		                    ->canBeUnset()
                            ->children()
								->scalarNode('class')->defaultValue('CCDNComponent\AttachmentBundle\Gateway\RegistryGateway')->end()							
							->end()
						->end()
					->end()
				->end()
			->end()
		;
		
		return $this;
	}
	
    /**
     *
     * @access private
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
	 * @return \CCDNComponent\AttachmentBundle\DependencyInjection\Configuration
     */
    private function addManagerSection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('manager_bag')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
						->scalarNode('class')->defaultValue('CCDNComponent\AttachmentBundle\Manager\Bag\ManagerBag')->end()							
					->end()
				->end()
                ->arrayNode('manager')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('folder')
		                    ->addDefaultsIfNotSet()
		                    ->canBeUnset()
                            ->children()
								->scalarNode('class')->defaultValue('CCDNComponent\AttachmentBundle\Manager\FolderManager')->end()							
							->end()
						->end()
                        ->arrayNode('attachment')
		                    ->addDefaultsIfNotSet()
		                    ->canBeUnset()
                            ->children()
								->scalarNode('class')->defaultValue('CCDNComponent\AttachmentBundle\Manager\AttachmentManager')->end()							
							->end()
						->end()
                        ->arrayNode('registry')
		                    ->addDefaultsIfNotSet()
		                    ->canBeUnset()
                            ->children()
								->scalarNode('class')->defaultValue('CCDNComponent\AttachmentBundle\Manager\RegistryManager')->end()							
							->end()
						->end()
					->end()
				->end()
			->end()
		;
		
		return $this;
	}
	
    /**
     *
     * @access private
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
	 * @return \CCDNComponent\AttachmentBundle\DependencyInjection\Configuration
     */
    private function addFormSection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('form')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('type')
		                    ->addDefaultsIfNotSet()
		                    ->canBeUnset()
                            ->children()
		                        ->arrayNode('attachment_upload')
				                    ->addDefaultsIfNotSet()
				                    ->canBeUnset()
		                            ->children()
										->scalarNode('class')->defaultValue('CCDNComponent\AttachmentBundle\Form\Type\AttachmentUploadFormType')->end()
									->end()
								->end()
							->end()
						->end()
                        ->arrayNode('handler')
		                    ->addDefaultsIfNotSet()
		                    ->canBeUnset()
                            ->children()
		                        ->arrayNode('attachment_upload')
				                    ->addDefaultsIfNotSet()
				                    ->canBeUnset()
		                            ->children()
										->scalarNode('class')->defaultValue('CCDNComponent\AttachmentBundle\Form\Handler\AttachmentUploadFormHandler')->end()					
									->end()
								->end()
							->end()
						->end()
                        ->arrayNode('validator')
		                    ->addDefaultsIfNotSet()
		                    ->canBeUnset()
                            ->children()
		                        ->arrayNode('upload_quota_disk_space')
				                    ->addDefaultsIfNotSet()
				                    ->canBeUnset()
		                            ->children()
										->scalarNode('class')->defaultValue('CCDNComponent\AttachmentBundle\Form\Validator\UploadQuotaDiskSpaceValidator')->end()							
									->end()
								->end()
		                        ->arrayNode('upload_quota_file_quantity')
				                    ->addDefaultsIfNotSet()
				                    ->canBeUnset()
		                            ->children()
										->scalarNode('class')->defaultValue('CCDNComponent\AttachmentBundle\Form\Validator\UploadQuotaFileQuantityValidator')->end()							
									->end()
								->end()
		                        ->arrayNode('upload_quota_file_size')
				                    ->addDefaultsIfNotSet()
				                    ->canBeUnset()
		                            ->children()
										->scalarNode('class')->defaultValue('CCDNComponent\AttachmentBundle\Form\Validator\UploadQuotaFileSizeValidator')->end()							
									->end()
								->end()

							->end()
						->end()
					->end()
				->end()
			->end()
		;
		
		return $this;
	}
	
    /**
     *
     * @access private
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
	 * @return \CCDNComponent\AttachmentBundle\DependencyInjection\Configuration
     */
    private function addComponentSection(ArrayNodeDefinition $node)
    {	
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('component')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
		                ->arrayNode('dashboard')
		                    ->addDefaultsIfNotSet()
		                    ->canBeUnset()
		                    ->children()
				                ->arrayNode('integrator')
				                    ->addDefaultsIfNotSet()
				                    ->canBeUnset()
				                    ->children()
										->scalarNode('class')->defaultValue('CCDNComponent\AttachmentBundle\Component\Dashboard\DashboardIntegrator')->end()							
									->end()		
								->end()
							->end()
						->end()
		                ->arrayNode('helper')
		                    ->addDefaultsIfNotSet()
		                    ->canBeUnset()
		                    ->children()
				                ->arrayNode('file_resolver')
				                    ->addDefaultsIfNotSet()
				                    ->canBeUnset()
				                    ->children()
										->scalarNode('class')->defaultValue('CCDNComponent\AttachmentBundle\Component\Helper\FileResolver')->end()							
									->end()		
								->end()
				                ->arrayNode('file_manager')
				                    ->addDefaultsIfNotSet()
				                    ->canBeUnset()
				                    ->children()
										->scalarNode('class')->defaultValue('CCDNComponent\AttachmentBundle\Component\Helper\FileManager')->end()							
									->end()		
								->end()
							->end()
						->end()
		                ->arrayNode('route_referer_ignore')
		                    ->addDefaultsIfNotSet()
		                    ->canBeUnset()
		                    ->children()
				                ->arrayNode('list')
				                    ->addDefaultsIfNotSet()
				                    ->canBeUnset()
				                    ->children()
										->scalarNode('class')->defaultValue('CCDNComponent\AttachmentBundle\Component\RouteRefererIgnore\RouteRefererIgnoreList')->end()							
									->end()
								->end()
							->end()		
						->end()
		                ->arrayNode('twig_extension')
		                    ->addDefaultsIfNotSet()
		                    ->canBeUnset()
		                    ->children()
				                ->arrayNode('get_attachment_quotas')
				                    ->addDefaultsIfNotSet()
				                    ->canBeUnset()
				                    ->children()
										->scalarNode('class')->defaultValue('CCDNComponent\AttachmentBundle\Component\TwigExtension\QuotaExtension')->end()							
									->end()
								->end()
							->end()		
						->end()
					->end()
				->end()
			->end()
		;
		
		return $this;
	}
	
    /**
     *
     * @access protected
     * @param ArrayNodeDefinition $node
	 * @return \CCDNComponent\AttachmentBundle\DependencyInjection\Configuration
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
            ->end()
		;
		
		return $this;
    }

    /**
     *
     * @access protected
     * @param ArrayNodeDefinition $node
	 * @return \CCDNComponent\AttachmentBundle\DependencyInjection\Configuration
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
                        ->scalarNode('max_total_quota')->defaultValue('100')->end()
                    ->end()
                ->end()
            ->end()
		;
		
		return $this;
    }

    /**
     *
     * @access protected
     * @param ArrayNodeDefinition $node
	 * @return \CCDNComponent\AttachmentBundle\DependencyInjection\Configuration
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
            ->end()
		;
		
		return $this;
    }
}
