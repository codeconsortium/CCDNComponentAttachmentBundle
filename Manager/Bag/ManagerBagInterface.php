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

namespace CCDNComponent\AttachmentBundle\Manager\Bag;

use CCDNComponent\AttachmentBundle\Manager\Bag\ManagerBagInterface;

use Symfony\Component\DependencyInjection\Container;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
interface ManagerBagInterface
{
	/**
	 *
	 * @access public
	 * @param \Symfony\Component\DependencyInjection\Container $container
	 */
    public function __construct(Container $container);
		
	/**
	 *
	 * @access public
	 * @return \CCDNComponent\AttachmentBundle\Manager\FolderManager
	 */
	public function getFolderManager();
	
	/**
	 *
	 * @access public
	 * @return \CCDNComponent\AttachmentBundle\Manager\AttachmentManager
	 */
	public function getAttachmentManager();

	
	/**
	 *
	 * @access public
	 * @return \CCDNComponent\AttachmentBundle\Manager\RegistryManager
	 */
	public function getRegistryManager();
	
	/**
	 *
	 * @access public
	 * @return int
	 */
	public function getAttachmentsPerPageOnFolders();
	
	/**
	 *
	 * @access public
	 * @return int
	 */
	public function getQuotaFileQuantity();
	
	/**
	 *
	 * @access public
	 * @return int
	 */
	public function getQuotaFileSize();
		
	/**
	 *
	 * @access public
	 * @return int
	 */
	public function getQuotaDiskSpace();
}