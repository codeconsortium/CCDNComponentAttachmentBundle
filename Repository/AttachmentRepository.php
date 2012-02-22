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
	 * @param int $board_id
	 */	
	public function findForUserById($user_id)
	{	
		$query = $this->getEntityManager()
			->createQuery('
				SELECT a, u FROM CCDNComponentAttachmentBundle:Attachment a
				LEFT JOIN a.created_by u
				WHERE a.created_by = :user_id
				GROUP BY a.id
				ORDER BY a.created_date DESC')
			->setParameter('user_id', $user_id);

		try {
			return new Pagerfanta(new DoctrineORMAdapter($query));
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        return null;
	    }	
	}

	
}