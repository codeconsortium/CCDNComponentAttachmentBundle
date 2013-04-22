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
 * @category CCDNComponent
 * @package  AttachmentBundle
 *
 * @author   Reece Fowell <reece@codeconsortium.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  Release: 2.0
 * @link     https://github.com/codeconsortium/CCDNComponentAttachmentBundle
 *
 */
abstract class BaseGateway implements BaseGatewayInterface
{
    /**
     *
     * @access protected
     * @var \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     */
    protected $doctrine;

    /**
     *
     * @access protected
     * @var \Doctrine\ORM\EntityManager $em
     */
    protected $em;

    /**
     *
     * @access protected
     * @var \CCDNComponent\AttachmentBundle\Gateway\Bag\GatewayBagInterface $gatewayBag
     */
    protected $gatewayBag;

    /**
     *
     * @access private
     * @var string $entityClass
     */
    protected $entityClass;

    /**
     *
     * @access public
     * @param \Doctrine\Bundle\DoctrineBundle\Registry                        $doctrine
     * @param \CCDNComponent\AttachmentBundle\Gateway\Bag\GatewayBagInterface $gatewayBag
     * @param string                                                          $entityClass
     */
    public function __construct(Registry $doctrine, GatewayBagInterface $gatewayBag, $entityClass)
    {
        $this->doctrine = $doctrine;

        $this->em = $doctrine->getEntityManager();

        $this->gatewayBag = $gatewayBag;

        if (null == $entityClass) {
            throw new \Exception('Entity class for gateway must be specified!');
        }

        $this->entityClass = $entityClass;
    }

    /**
     *
     * @access public
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     *
     * @access public
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->em->createQueryBuilder();
    }

    /**
     *
     * @access public
     * @param  \Doctrine\ORM\QueryBuilder                   $qb
     * @param  Array                                        $parameters
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function one(QueryBuilder $qb, $parameters = array())
    {
        if (count($parameters)) {
            $qb->setParameters($parameters);
        }

        try {
            return $qb->getQuery()->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     *
     * @access public
     * @param  \Doctrine\ORM\QueryBuilder                   $qb
     * @param  Array                                        $parameters
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function all(QueryBuilder $qb, $parameters = array())
    {
        if (count($parameters)) {
            $qb->setParameters($parameters);
        }

        try {
            return $qb->getQuery()->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     *
     * @access public
     * @param  \Doctrine\ORM\QueryBuilder $qb
     * @param  int                        $itemsPerPage
     * @param  int                        $page
     * @return \Pagerfanta\Pagerfanta
     */
    public function paginateQuery(QueryBuilder $qb, $itemsPerPage, $page)
    {
        try {
            $pager = new Pagerfanta(new DoctrineORMAdapter($qb));
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        } catch (\Exception $e) {
            return null;
        }

        $pager->setMaxPerPage($itemsPerPage);
        $pager->setCurrentPage($page, false, true);

        return $pager;
    }

    /**
     *
     * @access protected
     * @param $item
     * @return \CCDNComponent\AttachmentBundle\Gateway\BaseGatewayInterface
     */
    protected function persist($item)
    {
        $this->em->persist($item);

        return $this;
    }

    /**
     *
     * @access protected
     * @param $item
     * @return \CCDNComponent\AttachmentBundle\Gateway\BaseGatewayInterface
     */
    protected function remove($item)
    {
        $this->em->remove($item);

        return $this;
    }

    /**
     *
     * @access public
     * @return \CCDNComponent\AttachmentBundle\Gateway\BaseGatewayInterface
     */
    public function flush()
    {
        $this->em->flush();

        return $this;
    }
}
