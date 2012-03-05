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
				WHERE a.owned_by = :user_id AND a.id = :attachment_id
				GROUP BY a.id')
			->setParameters(array('attachment_id' => $attachmentId, 'user_id' => $user_id));

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
	public function findForUserById($user_id)
	{	
		$query = $this->getEntityManager()
			->createQuery('
				SELECT a, u FROM CCDNComponentAttachmentBundle:Attachment a
				LEFT JOIN a.owned_by u
				WHERE a.owned_by = :user_id
				GROUP BY a.id
				ORDER BY a.created_date DESC')
			->setParameter('user_id', $user_id);

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
	public function findForUserByIdAsArray($user_id)
	{	
		$query = $this->getEntityManager()
			->createQuery('
				SELECT a, u FROM CCDNComponentAttachmentBundle:Attachment a
				LEFT JOIN a.owned_by u
				WHERE a.owned_by = :user_id
				GROUP BY a.id
				ORDER BY a.created_date DESC')
			->setParameter('user_id', $user_id);

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
		$qb->add('select', 'a')
			->where($qb->expr()->eq('a.owned_by', '?1'))
		//	->orderBy('a.created_date', 'DESC')
			->setParameter('1', $userId);

		return $qb;
		
/*		try {
			return $query->getResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        return null;
	    }	*/
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
				$qb->expr()->eq('a.owned_by', '?1'),
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