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

namespace CCDNComponent\AttachmentBundle\Gateway\Bag;

use Symfony\Component\DependencyInjection\Container;

use CCDNComponent\AttachmentBundle\Gateway\Bag\GatewayBagInterface;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class GatewayBag implements GatewayBagInterface
{
	/**
	 *
	 * @access protected
	 * @var \CCDNComponent\AttachmentBundle\Gateway\FolderGateway $folderGateway
	 */
	protected $folderGateway;
	
	/**
	 *
	 * @access protected
	 * @var \CCDNComponent\AttachmentBundle\Gateway\AttachmentGateway $attachmentGateway
	 */
	protected $attachmentGateway;
	
	/**
	 *
	 * @access protected
	 * @var \CCDNComponent\AttachmentBundle\Gateway\RegistryGateway $registryGateway
	 */
	protected $registryGateway;

	/**
	 *
	 * @access protected
	 * @var \Symfony\Component\DependencyInjection\Container $container
	 */
    protected $container;

	/**
	 *
	 * @access public
	 * @param \Symfony\Component\DependencyInjection\Container $container
	 */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
	
	/**
	 *
	 * @access public
	 * @return \CCDNComponent\AttachmentBundle\Gateway\FolderGateway
	 */
	public function getFolderGateway()
	{
		if (null == $this->folderGateway) {
			$this->folderGateway = $this->container->get('ccdn_component_attachment.gateway.folder');
		}
		
		return $this->folderGateway;
	}
	
	/**
	 *
	 * @access public
	 * @return \CCDNComponent\AttachmentBundle\Gateway\AttachmentGateway
	 */
	public function getAttachmentGateway()
	{
		if (null == $this->attachmentGateway) {
			$this->attachmentGateway = $this->container->get('ccdn_component_attachment.gateway.attachment');
		}
		
		return $this->attachmentGateway;
	}
	
	/**
	 *
	 * @access public
	 * @return \CCDNComponent\AttachmentBundle\Gateway\RegistryGateway
	 */
	public function getRegistryGateway()
	{
		if (null == $this->registryGateway) {
			$this->registryGateway = $this->container->get('ccdn_component_attachment.gateway.registry');
		}
		
		return $this->registryGateway;		
	}
}