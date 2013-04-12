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

namespace CCDNComponent\AttachmentBundle\Gateway;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\QueryBuilder;

use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

use CCDNComponent\AttachmentBundle\Gateway\BaseGatewayInterface;
use CCDNComponent\AttachmentBundle\Gateway\Bag\GatewayBagInterface;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 * @abstract
 */
interface BaseGatewayInterface
{
	/**
	 *
	 * @access public
	 * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
	 * @param \CCDNComponent\AttachmentBundle\Gateway\Bag\GatewayBagInterface $gatewayBag
	 * @param string $entityClass
	 */
	public function __construct(Registry $doctrine, GatewayBagInterface $gatewayBag, $entityClass);

	/**
	 *
	 * @access public
	 * @return string
	 */
	public function getEntityClass();
	
	/**
	 *
	 * @access public
	 * @return \Doctrine\ORM\QueryBuilder
	 */	
	public function getQueryBuilder();
	
	/**
	 *
	 * @access public
	 * @param \Doctrine\ORM\QueryBuilder $qb
	 * @param Array $parameters
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */	
	public function one(QueryBuilder $qb, $parameters = array());
	
	/**
	 *
	 * @access public
	 * @param \Doctrine\ORM\QueryBuilder $qb
	 * @param Array $parameters
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */	
	public function all(QueryBuilder $qb, $parameters = array());
	
	/**
	 *
	 * @access public
	 * @param \Doctrine\ORM\QueryBuilder $qb
	 * @param int $itemsPerPage
	 * @param int $page
	 * @return \Pagerfanta\Pagerfanta
	 */
	public function paginateQuery(QueryBuilder $qb, $itemsPerPage, $page);
	
	/**
	 *
	 * @access public
	 * @return \CCDNComponent\AttachmentBundle\Gateway\BaseGatewayInterface
	 */
	public function flush();
}
