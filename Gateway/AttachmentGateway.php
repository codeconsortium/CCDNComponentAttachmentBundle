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

use Doctrine\ORM\QueryBuilder;

use CCDNComponent\AttachmentBundle\Gateway\BaseGatewayInterface;
use CCDNComponent\AttachmentBundle\Gateway\BaseGateway;

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
class AttachmentGateway extends BaseGateway implements BaseGatewayInterface
{
    /**
     *
     * @access private
     * @var string $queryAlias
     */
    protected $queryAlias = 'a';

    /**
     *
     * @access public
     * @param  \Doctrine\ORM\QueryBuilder                   $qb
     * @param  Array                                        $parameters
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findAttachment(QueryBuilder $qb = null, $parameters = null)
    {
        if (null == $qb) {
            $qb = $this->createSelectQuery();
        }

        return $this->one($qb, $parameters);
    }

    /**
     *
     * @access public
     * @param  \Doctrine\ORM\QueryBuilder                   $qb
     * @param  Array                                        $parameters
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findAttachments(QueryBuilder $qb = null, $parameters = null)
    {
        if (null == $qb) {
            $qb = $this->createSelectQuery();
        }

        //$qb
        //	->addOrderBy('a.uploadedDate', 'DESC')
        //;
        return $this->all($qb, $parameters);
    }

    /**
     *
     * @access public
     * @param  \Doctrine\ORM\QueryBuilder $qb
     * @param  Array                      $parameters
     * @return int
     */
    public function countAttachments(QueryBuilder $qb = null, $parameters = null)
    {
        if (null == $qb) {
            $qb = $this->createCountQuery();
        }

        if (null == $parameters) {
            $parameters = array();
        }

        $qb->setParameters($parameters);

        try {
            return $qb->getQuery()->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return 0;
        }
    }

    /**
     *
     * @access public
     * @param  \CCDNComponent\AttachmentBundle\Entity\Attachment            $attachment
     * @return \CCDNComponent\AttachmentBundle\Gateway\BaseGatewayInterface
     */
    public function persistAttachment(Attachment $attachment)
    {
        $this->persist($attachment)->flush();

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNComponent\AttachmentBundle\Entity\Attachment            $attachment
     * @return \CCDNComponent\AttachmentBundle\Gateway\BaseGatewayInterface
     */
    public function updateAttachment(Attachment $attachment)
    {
        $this->persist($attachment)->flush();

        return $this;
    }

    /**
     *
     * @access public
     * @param  \CCDNComponent\AttachmentBundle\Entity\Attachment            $attachment
     * @return \CCDNComponent\AttachmentBundle\Gateway\BaseGatewayInterface
     */
    public function deleteAttachment(Attachment $attachment)
    {
        $this->remove($attachment)->flush();

        return $this;
    }
}
