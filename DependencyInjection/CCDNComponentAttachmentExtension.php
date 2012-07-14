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

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 * 
 * @author Reece Fowell <reece@codeconsortium.com> 
 * @version 1.0
 */
class CCDNComponentAttachmentExtension extends Extension
{


    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
		$processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

		$container->setParameter('ccdn_component_attachment.template.engine', $config['template']['engine']);
		$container->setParameter('ccdn_component_attachment.user.profile_route', $config['user']['profile_route']);
		$container->setParameter('ccdn_component_attachment.store.dir', $config['store']['dir']);
		
		$this->getSEOSection($container, $config);
		$this->getQuotaSection($container, $config);
		$this->getAttachmentSection($container, $config);
		
    }
	
	
	
	/**
	 *
	 * @access protected
	 * @param $container, $config
	 */
	protected function getSEOSection($container, $config)
	{
	    $container->setParameter('ccdn_component_attachment.seo.title_length', $config['seo']['title_length']);
	}	
	
	
	
	/**
	 *
	 * @access protected
	 * @param $container, $config
	 */
	protected function getQuotaSection($container, $config)
	{
	    $container->setParameter('ccdn_component_attachment.quota_per_user.max_files_quantity', $config['quota_per_user']['max_files_quantity']);
	    $container->setParameter('ccdn_component_attachment.quota_per_user.max_filesize_per_file', $config['quota_per_user']['max_filesize_per_file']);
	    $container->setParameter('ccdn_component_attachment.quota_per_user.max_total_quota', $config['quota_per_user']['max_total_quota']);
	}
	
	
	
	/**
	 *
	 * @access protected
	 * @param $container, $config
	 */
	protected function getAttachmentSection($container, $config)
	{
	    $container->setParameter('ccdn_component_attachment.manage.list.layout_template', $config['manage']['list']['layout_template']);
	    $container->setParameter('ccdn_component_attachment.manage.list.attachments_per_page', $config['manage']['list']['attachments_per_page']);
	    $container->setParameter('ccdn_component_attachment.manage.list.attachment_uploaded_datetime_format', $config['manage']['list']['attachment_uploaded_datetime_format']);
	
	    $container->setParameter('ccdn_component_attachment.manage.upload.layout_template', $config['manage']['upload']['layout_template']);
	    $container->setParameter('ccdn_component_attachment.manage.upload.form_theme', $config['manage']['upload']['form_theme']);
	}
	
	
	
    /**
     * {@inheritDoc}
     */
	public function getAlias()
	{
		return 'ccdn_component_attachment';
	}

	
}
