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

use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\QueryBuilder;

use CCDNComponent\AttachmentBundle\Manager\BaseManagerInterface;
use CCDNComponent\AttachmentBundle\Manager\Bag\ManagerBagInterface;

use CCDNComponent\AttachmentBundle\Gateway\BaseGatewayInterface;

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
 * @abstract
 */
abstract class BaseManager implements BaseManagerInterface
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
     * @var \Symfony\Component\Security\Core\SecurityContext $securityContext
     */
    protected $securityContext;

    /**
     *
     * @access protected
     * @var \CCDNComponent\AttachmentBundle\Manager\BaseManagerInterface $gateway
     */
    protected $gateway;

    /**
     *
     * @access protected
     * @var \CCDNComponent\AttachmentBundle\Manager\Bag\ManagerBagInterface $managerBag
     */
    protected $managerBag;

    /**
     *
     * @access public
     * @param \Doctrine\Bundle\DoctrineBundle\Registry                        $doctrine
     * @param \Symfony\Component\Security\Core\SecurityContext                $securityContext
     * @param \CCDNComponent\AttachmentBundle\Gateway\BaseGatewayInterface    $gateway
     * @param \CCDNComponent\AttachmentBundle\Manager\Bag\ManagerBagInterface $managerBag
     */
    public function __construct(Registry $doctrine, SecurityContext $securityContext, BaseGatewayInterface $gateway, ManagerBagInterface $managerBag)
    {
        $this->doctrine = $doctrine;

        $this->em = $doctrine->getEntityManager();

        $this->securityContext = $securityContext;

        $this->gateway = $gateway;

        $this->managerBag = $managerBag;
    }

    /**
     *
     * @access public
     * @param  string $role
     * @return bool
     */
    public function isGranted($role)
    {
        return $this->securityContext->isGranted($role);
    }

    /**
     *
     * @access public
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function getUser()
    {
        return $this->securityContext->getToken()->getUser();
    }

    /**
     *
     * @access public
     * @return \CCDNComponent\AttachmentBundle\Gateway\BaseGatewayInterface
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     *
     * @access public
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->gateway->getQueryBuilder();
    }

    /**
     *
     * @access public
     * @param  string                                       $column  = null
     * @param  Array                                        $aliases = null
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function createCountQuery($column = null, Array $aliases = null)
    {
        return $this->gateway->createCountQuery($column, $aliases);
    }

    /**
     *
     * @access public
     * @param  Array                                        $aliases = null
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function createSelectQuery(Array $aliases = null)
    {
        return $this->gateway->createSelectQuery($aliases);
    }

    /**
     *
     * @access public
     * @param  \Doctrine\ORM\QueryBuilder                   $qb
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function one(QueryBuilder $qb)
    {
        return $this->gateway->one($qb);
    }

    /**
     *
     * @access public
     * @param  \Doctrine\ORM\QueryBuilder $qb
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function all(QueryBuilder $qb)
    {
        return $this->gateway->all($qb);
    }

    /**
     *
     * @access public
     * @param $entity
     * @return \CCDNComponent\AttachmentBundle\Manager\BaseManagerInterface
     */
    public function persist($entity)
    {
        $this->em->persist($entity);

        return $this;
    }

    /**
     *
     * @access public
     * @param $entity
     * @return \CCDNComponent\AttachmentBundle\Manager\BaseManagerInterface
     */
    public function remove($entity)
    {
        $this->em->remove($entity);

        return $this;
    }

    /**
     *
     * @access public
     * @return \CCDNComponent\AttachmentBundle\Manager\BaseManagerInterface
     */
    public function flush()
    {
        $this->em->flush();

        return $this;
    }

    /**
     *
     * @access public
     * @param $entity
     * @return \CCDNComponent\AttachmentBundle\Manager\BaseManagerInterface
     */
    public function refresh($entity)
    {
        $this->em->refresh($entity);

        return $this;
    }

    /**
     *
     * @access public
     * @return int
     */
    public function getAttachmentsPerPageOnFolders()
    {
        return $this->managerBag->getAttachmentsPerPageOnFolders();
    }

    /**
     *
     * @access public
     * @return int
     */
    public function getQuotaFileQuantity()
    {
        return $this->managerBag->getQuotaFileQuantity();
    }

    /**
     *
     * @access public
     * @return int
     */
    public function getQuotaFileSize()
    {
        return $this->managerBag->getQuotaFileSize();
    }

    /**
     *
     * @access public
     * @return int
     */
    public function getQuotaDiskSpace()
    {
        return $this->managerBag->getQuotaDiskSpace();
    }
}
