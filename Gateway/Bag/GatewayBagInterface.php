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

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
interface GatewayBagInterface
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
	 * @return \CCDNComponent\AttachmentBundle\Gateway\AttachmentGateway
	 */
	public function getAttachmentGateway();
	
	/**
	 *
	 * @access public
	 * @return \CCDNComponent\AttachmentBundle\Gateway\RegistryGateway
	 */
	public function getRegistryGateway();
}