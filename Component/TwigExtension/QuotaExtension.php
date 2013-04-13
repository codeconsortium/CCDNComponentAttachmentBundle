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

namespace CCDNComponent\AttachmentBundle\Component\TwigExtension;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * 
 * @author Reece Fowell <reece@codeconsortium.com> 
 * @version 1.0
 */
class QuotaExtension extends \Twig_Extension
{	
	/**
	 *
	 * @access protected
	 * @param \CCDNComponent\AttachmentBundle\Manager\BaseManagerInterface
	 */
	protected $attachmentManager;
	
	/**
	 *
	 * @access public
	 * @param \CCDNComponent\AttachmentBundle\Manager\BaseManagerInterface $attachmentManager
	 */
	public function __construct($attachmentManager)
	{
		$this->attachmentManager = $attachmentManager;
	}

	/**
	 *
	 * @access public
	 * @return array
	 */
	public function getFunctions()
	{
		return array(
			'getAttachmentQuotas' => new \Twig_Function_Method($this, 'getAttachmentQuotas'),
		);
	}
	
	/**
	 *
	 * @access public
	 * @return string
	 */
	public function getName()
	{
		return 'get_attachment_quotas';
	}
	
	/**
	 *
	 * @access public
	 * @param \Symfony\Component\Security\Core\User\UserInterface $user
	 * @return array
	 */
	public function getAttachmentQuotas(UserInterface $user)
	{
		return $this->attachmentManager->calculateQuotasForUser($user);
	}
}