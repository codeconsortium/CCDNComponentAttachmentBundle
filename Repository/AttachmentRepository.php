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

namespace CCDNComponent\AttachmentBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * AttachmentRepository
 * 
 * @author Reece Fowell <reece@codeconsortium.com> 
 * @version 1.0
 */
class AttachmentRepository extends EntityRepository
{
	
	
	
	/**
	 *
	 * @access public
	 * @param int $user_id
	 */	
	public function findSingleAttachmentForUserById($attachmentId, $userId)
	{
		$query = $this->getEntityManager()
			->createQuery('
				SELECT a, FROM CCDNComponentAttachmentBundle:Attachment a
				WHERE a.ownedBy = :userId AND a.id = :attachmentId
				GROUP BY a.id')
			->setParameters(array('attachmentId' => $attachmentId, 'userId' => $userId));

		try {
			return $query->fetchSingleResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        return null;
	    }
	}
	
	
	
	/**
	 *
	 * @access public
	 * @param int $user_id
	 */	
	public function findForUserById($userId)
	{	
		$query = $this->getEntityManager()
			->createQuery('
				SELECT a, u FROM CCDNComponentAttachmentBundle:Attachment a
				LEFT JOIN a.ownedBy u
				WHERE a.ownedBy = :userId
				GROUP BY a.id
				ORDER BY a.createdDate DESC')
			->setParameter('userId', $userId);

		try {
			return new Pagerfanta(new DoctrineORMAdapter($query));
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        return null;
	    }	
	}
	
	
	
	/**
	 *
	 * @access public
	 * @param int $user_id
	 */	
	public function findForUserByIdAsArray($userId)
	{	
		$query = $this->getEntityManager()
			->createQuery('
				SELECT a, u FROM CCDNComponentAttachmentBundle:Attachment a
				LEFT JOIN a.ownedBy u
				WHERE a.ownedBy = :userId
				GROUP BY a.id
				ORDER BY a.createdDate DESC')
			->setParameter('userId', $userId);

		try {
			return $query->getResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        return null;
	    }	
	}


    /**
   	 *
   	 * @access public
   	 * @param int $user_id
   	 */
   	public function findForUserByIdAsQB($userId)
   	{
   		$qb = $this->getEntityManager()->createQueryBuilder('a');
   		$qb->select('a')
   			->from('CCDNComponentAttachmentBundle:Attachment', 'a')
   			->where($qb->expr()->eq('a.ownedBy', '?1'))
   			->setParameter('1', $userId);

   		try {
   			return $qb->getQuery()->getResult();
   	    } catch (\Doctrine\ORM\NoResultException $e) {
   	        return null;
   	    }
   	}
	
	
	
	/**
	 *
	 * @access public
	 * @param array $objectIds, int $userId
	 */
	public function findTheseAttachmentsByUserId($objectIds, $userId)
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
		$query = $qb->add('select', 'a')
			->from('CCDNComponentAttachmentBundle:Attachment', 'a')
			->where($qb->expr()->andx(
				$qb->expr()->eq('a.ownedBy', '?1'),
				$qb->expr()->in('a.id', '?2')))
			->setParameters(array('1' => $userId, '2' => array_values($objectIds)))
			->getQuery();
			
		try {
			return $query->getResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        return null;
	    }
	}
	
	
	
}