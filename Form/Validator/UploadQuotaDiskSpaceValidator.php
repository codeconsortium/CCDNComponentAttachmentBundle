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

namespace CCDNComponent\AttachmentBundle\Form\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

/**
 * 
 * @author Reece Fowell <reece@codeconsortium.com> 
 * @version 1.1
 * 
 * @see http://symfony.com/doc/current/cookbook/validation/custom_constraint.html
 */
class UploadQuotaDiskSpaceValidator extends ConstraintValidator
{
	
	
	/**
	 *
	 * @access protected
	 */
	protected $doctrine;
	
	
	/**
	 *
	 * @access protected
	 */
	protected $container;


	/**
	 *
	 * @access public
	 * @param $doctrine, $service_container
	 */
	public function __construct($doctrine, $service_container)
	{
		
		$this->doctrine = $doctrine;
		$this->container = $service_container;
	}
	
	
	/**
	 *
	 * @access public
	 * @param $file, Constraint $constraint
	 * @return bool
	 */
	public function isValid($file, Constraint $constraint)
	{
		if ($file)
		{
			// Get the user instance
			$user = $this->container->get('security.context')->getToken()->getUser();
			
			// get the SI Units calculator.
			$calc = $this->container->get('ccdn_component_common.bin.si.units');

			// check if the max_total_quota_in_kb is reached
			$maxTotalQuota = $this->container->getParameter('ccdn_component_attachment.quota_per_user.max_total_quota');
			
			$maxTotalQuotaInKiB = $calc->formatToSIUnit($maxTotalQuota, $calc::KiB, false);

			// work out total used so far.
			$totalUsedSpaceInKiB = 0;

			// Get all attachments for user
			$attachments = $this->container->get('ccdn_component_attachment.attachment.repository')->findForUserById($user->getId());
			
			foreach($attachments as $key => $attachment)
			{
				$totalUsedSpaceInKiB += $calc->formatToSIUnit($attachment->getFileSize(), $calc::KiB, false);
			}

			if ($totalUsedSpaceInKiB > $maxTotalQuotaInKiB)
			{
				$constraint->addFileDiskSpaceLimitReached();

				$this->setMessage($constraint->message);

				return false;
			} else {
				return true;
			}
		} else {
			return true;
		}
	}
	
}