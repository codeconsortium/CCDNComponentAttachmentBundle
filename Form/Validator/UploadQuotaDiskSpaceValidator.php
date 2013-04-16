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

use CCDNComponent\AttachmentBundle\Manager\BaseManagerInterface;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 2.0
 *
 * @see http://symfony.com/doc/current/cookbook/validation/custom_constraint.html
 */
class UploadQuotaDiskSpaceValidator extends ConstraintValidator
{
    /**
     *
     * @access protected
	 * @var \CCDNComponent\AttachmentBundle\Manager\BaseManagerInterface $attachmentManager
     */
    protected $attachmentManager;

    /**
     *
     * @access protected
	 * @var Object $binSICalculator
     */
	protected $binSICalculator;
	
    /**
     *
     * @access public
	 * @param \CCDNComponent\AttachmentBundle\Manager\BaseManagerInterface $attachmentManager
     */
    public function __construct(BaseManagerInterface $attachmentManager, $binSICalculator)
    {
        $this->attachmentManager = $attachmentManager;
		$this->binSICalculator = $binSICalculator;
    }
	
    /**
     *
     * @access public
     * @param \CCDNComponent\AttachmentBundle\Entity\Attachment $attachment
	 * @param \Symfony\Component\Validator\Constraint $constraint
     * @return bool
     */
    public function validate($attachment, Constraint $constraint)
    {
		$calc = $this->binSICalculator;
		$sizeOfFile = $calc->formatToSIUnit(filesize($attachment->getAttachment()), $calc::KiB, false);

		$quotas = $this->attachmentManager->calculateQuotasForUserAndRetain($attachment->getOwnedByUser());
		$quotas['totalKiBUsed'] += $sizeOfFile;

		if ($quotas['totalKiBUsed'] > $quotas['totalKiBQuota']) {
			$this->context->addViolationAtSubPath('attachment', $constraint->message, array(), null);
		}
    }
}
