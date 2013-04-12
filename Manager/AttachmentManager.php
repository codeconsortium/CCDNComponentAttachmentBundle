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

namespace CCDNComponent\AttachmentBundle\Manager;

use CCDNComponent\AttachmentBundle\Manager\BaseManagerInterface;
use CCDNComponent\AttachmentBundle\Manager\BaseManager;

use CCDNComponent\AttachmentBundle\Entity\Attachment;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class AttachmentManager extends BaseManager implements BaseManagerInterface
{
	public function findOneAttachmentByPublicKey($publicKey)
	{
		if (null == $publicKey) {
			throw new \Exception('Attachment Public Hash Key: "' . $publicKey . '" is invalid!');
		}
		
		$params = array(':publicKey' => $publicKey);
	
		$qb = $this->createSelectQuery(array('a', 'a_owned_by'));
	
		$qb
			->join('a.ownedByUser', 'a_owned_by')
			->where('a.publicKey = :publicKey')
		;
		
		return $this->gateway->findAttachment($qb, $params);
	}
	
	/**
	 *
	 * @access public
	 * @param int $userId
	 * @param int $page
	 * @return \Pagerfanta\Pagerfanta
	 */	
 	public function findAllAttachmentsPaginatedForUserById($userId, $page)
	{
		if (null == $userId || ! is_numeric($userId) || $userId == 0) {
			throw new \Exception('User id "' . $userId . '" is invalid!');
		}
		
		$params = array(':userId' => $userId);
	
		$qb = $this->createSelectQuery(array('a', 'a_owned_by'));
	
		$qb
			->join('a.ownedByUser', 'a_owned_by')
			->where('a.ownedByUser = :userId')
			->setParameters($params)
			->addOrderBy('a.createdDate', 'DESC');

		return $this->gateway->paginateQuery($qb, $this->getAttachmentsPerPageOnFolders(), $page);
	}
	
    /**
     *
     * @access public
     * @param string $attachment
     * @return self
     */
    public function saveUpload(Attachment $attachment)
    {
        $this->persist($attachment)->flush();

        return $this;
    }

    /**
     * @access public
     * @param array $attachments
     * @return self
     * @link http://www.php.net/manual/en/function.unlink.php
     */
    public function bulkDelete($attachments)
    {
        $fileStore = $this->container->getParameter('ccdn_component_attachment.store.dir') . '/';

        foreach ($attachments as $key => $attachment) {
            if (@unlink(realpath($fileStore . $attachment->getFilenameHashed()))) {
                $this->remove($attachment);
            }
        }

        return $this;
    }

    /**
     *
     * @access public
     * @param  array $attachments
     * @return int
     */
    public function getTotalQuotaInKiB($attachments, $calc)
    {
        // work out total used so far.
        $totalUsedSpaceInKiB = 0;

        foreach ($attachments as $key => $attachment) {
            $totalUsedSpaceInKiB += $calc->formatToSIUnit($attachment->getFileSize(), $calc::KiB, false);
        }

        return $totalUsedSpaceInKiB;
    }

    /**
     *
     * @access public
     * @param  array $attachments
     * @return int
     */
    public function getTotalQuantityQuota($attachments)
    {
        return count($attachments);
    }

    /**
     *
     * @access public
     * @param  array $attachments
     * @return array
     */
    public function calculateQuotasForUser($user)
    {
        //$attachments = $this->container->get('ccdn_component_attachment.repository.attachment')->findForUserByIdAsArray($user->getId());
		$attachments = array();

        //$calc = $this->container->get('ccdn_component_common.component.helper.bin_si_units');

        // get max_files_quantity quota
        $maxQuotaQuantity = $this->managerBag->getQuotaFileQuantity();//$this->container->getParameter('ccdn_component_attachment.quota_per_user.max_files_quantity');

        // get max_total_quota_in_kb quota
        $maxQuotaSpace = $this->managerBag->getQuotaDiskSpace();//$this->container->getParameter('ccdn_component_attachment.quota_per_user.max_total_quota');
        $maxQuotaSpaceInKiB = 99;//$calc->formatToSIUnit($maxQuotaSpace, $calc::KiB, false);

        $usedQuotaQuantity = 0;//$this->getTotalQuantityQuota($attachments);
        $usedQuotaSpaceInKiB = 0;//$this->getTotalQuotaInKiB($attachments, $calc);

        $results = array(
            'maxQuantity' => $maxQuotaQuantity,
            'usedQuantity' => $usedQuotaQuantity,
            'usedQuantityPercent' => round(($usedQuotaQuantity / $maxQuotaQuantity) * 100),
            'maxKiB' => $maxQuotaSpaceInKiB,
            'usedKiB' => $usedQuotaSpaceInKiB,
            'usedKiBPercent' => round(($usedQuotaSpaceInKiB / $maxQuotaSpaceInKiB) * 100),
        );

        return $results;
    }
}
