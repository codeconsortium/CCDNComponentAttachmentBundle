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

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;

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
	 *
     * @access public
	 * @return string
     */
    public function getAlias()
    {
        return 'ccdn_component_attachment';
    }

    /**
     *
     * @access public
	 * @param array $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

		// Class file namespaces.
        $this
			->getEntitySection($config, $container)
	        ->getRepositorySection($config, $container)
	        ->getGatewaySection($config, $container)
	        ->getManagerSection($config, $container)
	        ->getFormSection($config, $container)
			->getComponentSection($config, $container)
		;
			
		// Configuration stuff.
        $container->setParameter('ccdn_component_attachment.template.engine', $config['template']['engine']);
        $container->setParameter('ccdn_component_attachment.store.dir', $config['store']['dir']);

        $this
			->getSEOSection($config, $container)
	        ->getQuotaSection($config, $container)
	        ->getAttachmentSection($config, $container)
		;
		
		// Load Service definitions.
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
	
    /**
     *
     * @access private
	 * @param array $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
	 * @return \CCDNComponent\AttachmentBundle\DependencyInjection\CCDNComponentAttachmentExtension
     */
    private function getEntitySection(array $config, ContainerBuilder $container)
    {
        $container->setParameter('ccdn_component_attachment.entity.folder.class', $config['entity']['folder']['class']);
        $container->setParameter('ccdn_component_attachment.entity.attachment.class', $config['entity']['attachment']['class']);
        $container->setParameter('ccdn_component_attachment.entity.registry.class', $config['entity']['registry']['class']);
		
		return $this;
	}
	
    /**
     *
     * @access private
	 * @param array $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
	 * @return \CCDNComponent\AttachmentBundle\DependencyInjection\CCDNComponentAttachmentExtension
     */
    private function getRepositorySection(array $config, ContainerBuilder $container)
    {
        $container->setParameter('ccdn_component_attachment.repository.folder.class', $config['repository']['folder']['class']);
        $container->setParameter('ccdn_component_attachment.repository.attachment.class', $config['repository']['attachment']['class']);
        $container->setParameter('ccdn_component_attachment.repository.registry.class', $config['repository']['registry']['class']);
		
		return $this;
	}
	
    /**
     *
     * @access private
	 * @param array $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
	 * @return \CCDNComponent\AttachmentBundle\DependencyInjection\CCDNComponentAttachmentExtension
     */
    private function getGatewaySection(array $config, ContainerBuilder $container)
    {
        $container->setParameter('ccdn_component_attachment.gateway_bag.class', $config['gateway_bag']['class']);

        $container->setParameter('ccdn_component_attachment.gateway.folder.class', $config['gateway']['folder']['class']);
        $container->setParameter('ccdn_component_attachment.gateway.attachment.class', $config['gateway']['attachment']['class']);
        $container->setParameter('ccdn_component_attachment.gateway.registry.class', $config['gateway']['registry']['class']);
		
		return $this;
	}
	
    /**
     *
     * @access private
	 * @param array $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
	 * @return \CCDNComponent\AttachmentBundle\DependencyInjection\CCDNComponentAttachmentExtension
     */
    private function getManagerSection(array $config, ContainerBuilder $container)
    {
        $container->setParameter('ccdn_component_attachment.manager_bag.class', $config['manager_bag']['class']);

        $container->setParameter('ccdn_component_attachment.manager.folder.class', $config['manager']['folder']['class']);
        $container->setParameter('ccdn_component_attachment.manager.attachment.class', $config['manager']['attachment']['class']);
        $container->setParameter('ccdn_component_attachment.manager.registry.class', $config['manager']['registry']['class']);
		
		return $this;
	}
	
    /**
     *
     * @access private
	 * @param array $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
	 * @return \CCDNComponent\AttachmentBundle\DependencyInjection\CCDNComponentAttachmentExtension
     */
    private function getFormSection(array $config, ContainerBuilder $container)
    {
        $container->setParameter('ccdn_component_attachment.form.type.attachment_upload.class', $config['form']['type']['attachment_upload']['class']);
        $container->setParameter('ccdn_component_attachment.form.handler.attachment_upload.class', $config['form']['handler']['attachment_upload']['class']);
		
        $container->setParameter('ccdn_component_attachment.form.validator.upload_quota_disk_space.class', $config['form']['validator']['upload_quota_disk_space']['class']);
        $container->setParameter('ccdn_component_attachment.form.validator.upload_quota_file_quantity.class', $config['form']['validator']['upload_quota_file_quantity']['class']);
        $container->setParameter('ccdn_component_attachment.form.validator.upload_quota_file_size.class', $config['form']['validator']['upload_quota_file_size']['class']);
		
		return $this;
	}

    /**
     *
     * @access private
	 * @param array $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
	 * @return \CCDNComponent\AttachmentBundle\DependencyInjection\CCDNComponentAttachmentExtension
     */
    private function getComponentSection(array $config, ContainerBuilder $container)
    {	
        $container->setParameter('ccdn_component_attachment.component.twig_extension.get_attachment_quotas.class', $config['component']['twig_extension']['get_attachment_quotas']['class']);		

        $container->setParameter('ccdn_component_attachment.component.helper.file_resolver.class', $config['component']['helper']['file_resolver']['class']);		
        $container->setParameter('ccdn_component_attachment.component.helper.file_manager.class', $config['component']['helper']['file_manager']['class']);		

        $container->setParameter('ccdn_component_attachment.component.dashboard.integrator.class', $config['component']['dashboard']['integrator']['class']);		
        $container->setParameter('ccdn_component_attachment.component.route_referer_ignore.list.class', $config['component']['route_referer_ignore']['list']['class']);		
		
		return $this;
	}
	
    /**
     *
     * @access private
	 * @param array $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
	 * @return \CCDNComponent\AttachmentBundle\DependencyInjection\CCDNComponentAttachmentExtension
     */
    private function getSEOSection(array $config, ContainerBuilder $container)
    {
        $container->setParameter('ccdn_component_attachment.seo.title_length', $config['seo']['title_length']);
		
		return $this;
    }

    /**
     *
     * @access private
	 * @param array $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
	 * @return \CCDNComponent\AttachmentBundle\DependencyInjection\CCDNComponentAttachmentExtension
     */
    private function getQuotaSection(array $config, ContainerBuilder $container)
    {
        $container->setParameter('ccdn_component_attachment.quota_per_user.max_files_quantity', $config['quota_per_user']['max_files_quantity']);
        $container->setParameter('ccdn_component_attachment.quota_per_user.max_filesize_per_file', $config['quota_per_user']['max_filesize_per_file']);
        $container->setParameter('ccdn_component_attachment.quota_per_user.max_total_quota', $config['quota_per_user']['max_total_quota']);
		
		return $this;
    }

    /**
     *
     * @access private
	 * @param array $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
	 * @return \CCDNComponent\AttachmentBundle\DependencyInjection\CCDNComponentAttachmentExtension
     */
    private function getAttachmentSection(array $config, ContainerBuilder $container)
    {
        $container->setParameter('ccdn_component_attachment.manage.list.layout_template', $config['manage']['list']['layout_template']);
        $container->setParameter('ccdn_component_attachment.manage.list.attachments_per_page', $config['manage']['list']['attachments_per_page']);
        $container->setParameter('ccdn_component_attachment.manage.list.attachment_uploaded_datetime_format', $config['manage']['list']['attachment_uploaded_datetime_format']);

        $container->setParameter('ccdn_component_attachment.manage.upload.layout_template', $config['manage']['upload']['layout_template']);
        $container->setParameter('ccdn_component_attachment.manage.upload.form_theme', $config['manage']['upload']['form_theme']);
		
		return $this;
    }
}
