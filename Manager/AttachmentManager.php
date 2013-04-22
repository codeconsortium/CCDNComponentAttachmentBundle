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

use Symfony\Component\Security\Core\User\UserInterface;

use CCDNComponent\AttachmentBundle\Manager\BaseManagerInterface;
use CCDNComponent\AttachmentBundle\Manager\BaseManager;

use CCDNComponent\AttachmentBundle\Entity\Attachment;

/**
 *
 * @category CCDNComponent
 * @package  AttachmentBundle
 *
 * @author   Reece Fowell <reece@codeconsortium.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  Release: 2.0
 * @link     https://github.com/codeconsortium/CCDNComponentAttachmentBundle
 *
 */
class AttachmentManager extends BaseManager implements BaseManagerInterface
{
    /**
     *
     * @access public
     * @param  int   $publicKey
     * @return array
     */
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
     * @param  int   $userId
     * @return array
     */
     public function findAllAttachmentsForUserById($userId)
    {
        if (null == $userId || ! is_numeric($userId) || $userId == 0) {
            throw new \Exception('User id "' . $userId . '" is invalid!');
        }

        $params = array(':userId' => $userId);

        $qb = $this->createSelectQuery(array('a', 'a_owned_by'));

        $qb
            ->join('a.ownedByUser', 'a_owned_by')
            ->where('a.ownedByUser = :userId')
            ->addOrderBy('a.createdDate', 'DESC')
        ;

        return $this->gateway->findAttachments($qb, $params);
    }

    /**
     *
     * @access public
     * @param  int                    $userId
     * @param  int                    $page
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
     * @param  arrays                 $attachmentIds
     * @param  int                    $userId
     * @return \Pagerfanta\Pagerfanta
     */
     public function findTheseAttachmentsByUserId($attachmentIds, $userId)
    {
        if (null == $userId || ! is_numeric($userId) || $userId == 0) {
            throw new \Exception('User id "' . $userId . '" is invalid!');
        }

        if (null == $attachmentIds || ! is_array($attachmentIds) || count($attachmentIds) < 1) {
            throw new \Exception('Attachment Ids must be an array and contain at least one id');
        }

        $params = array(':userId' => $userId);

        $qb = $this->createSelectQuery(array('a'));

        $qb
            ->where('a.ownedByUser = :userId')
            ->andWhere($qb->expr()->in('a.id', $attachmentIds))
            ->setParameters($params)
            ->addOrderBy('a.createdDate', 'DESC')
        ;

        return $this->gateway->findAttachments($qb, $params);
    }

    /**
     *
     * @access public
     * @param  int   $userId
     * @return Array
     */
    public function getAttachmentCountForUserById($userId)
    {
        if (null == $userId || ! is_numeric($userId) || $userId == 0) {
            throw new \Exception('User id "' . $userId . '" is invalid!');
        }

        $qb = $this->createSelectQuery(array('a'));

        $qb
            ->select('COUNT(DISTINCT a.id) AS attachmentCount')
            ->where('a.ownedByUser = :userId')
            ->setParameters(array(':userId' => $userId))
        ;

        try {
            return $qb->getQuery()->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return array('attachmentCount' => null);
        } catch (\Exception $e) {
            return array('attachmentCount' => null);
        }
    }

    /**
     *
     * @access public
     * @param  string $attachment
     * @return self
     */
    public function saveUpload(Attachment $attachment)
    {
        $this->persist($attachment)->flush();

        return $this;
    }

    /**
     *
     * @access public
     * @param  array $attachments
     * @return self
     */
    public function bulkDelete($attachments)
    {
        $fileManager = $this->managerBag->getFileManager();

        foreach ($attachments as $key => $attachment) {
            if ($fileManager->deleteFile($attachment)) {
                $this->remove($attachment);
            }
        }

        $this->flush();

        return $this;
    }

    /**
     *
     * @access public
     * @param  array $attachments
     * @return int
     */
    public function getTotalFileQuotaUsedInKiB($attachments, $calc)
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
    public function getTotalFileQuantity($attachments)
    {
        return count($attachments);
    }

    /**
     *
     * @access protected
     * @param array $preCalculatedQuotas
     */
    protected $preCalculatedQuotas;

    /**
     *
     * @access public
     * @param  array $attachments
     * @return array
     */
    public function calculateQuotasForUserAndRetain(UserInterface $user)
    {
        if (null == $this->preCalculatedQuotas) {
            $this->preCalculatedQuotas = $this->calculateQuotasForUser($user);
        }

        return $this->preCalculatedQuotas;
    }

    /**
     *
     * @access public
     * @param  array $attachments
     * @return array
     */
    public function calculateQuotasForUser(UserInterface $user)
    {
        $attachments = $this->findAllAttachmentsForUserById($user->getId());

        $calc = $this->managerBag->getSIUnitCalc();

        // get max_files_quantity quota
        $fileSizeQuota = $this->managerBag->getQuotaFileSize();
        $fileQuantityQuota = $this->managerBag->getQuotaFileQuantity();
        $fileQuantityUsed = $this->getTotalFileQuantity($attachments);

        // get max_total_quota_in_kb quota
        $totalSpaceQuota = $this->managerBag->getQuotaDiskSpace();
        $totalSpaceInKiBQuota = $totalSpaceQuota;
        $totalSpaceInKiBUsed = $this->getTotalFileQuotaUsedInKiB($attachments, $calc);

        $results = array(
            'fileSizeQuota' => $fileSizeQuota,
            'fileQuantityQuota' => $fileQuantityQuota,
            'fileQuantityUsed' => $fileQuantityUsed,
            'fileQuantityUsedPercent' => round(($fileQuantityUsed / $fileQuantityQuota) * 100),
            'totalKiBQuota' => $totalSpaceInKiBQuota,
            'totalKiBUsed' => $totalSpaceInKiBUsed,
            'totalKiBUsedPercent' => round(($totalSpaceInKiBUsed / $totalSpaceInKiBQuota) * 100),
        );

        return $results;
    }
}
