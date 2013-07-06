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

namespace CCDNComponent\AttachmentBundle\Manager\Bag;

use CCDNComponent\AttachmentBundle\Manager\Bag\ManagerBagInterface;

use Symfony\Component\DependencyInjection\Container;

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
class ManagerBag implements ManagerBagInterface
{
    /**
     *
     * @access protected
     * @var \Symfony\Component\DependencyInjection\Container $container
     */
    protected $container;

    /**
     *
     * @access protected
     * @var \CCDNComponent\AttachmentBundle\Manager\AttachmentManager $attachmentManager
     */
    protected $attachmentManager;

    /**
     *
     * @access protected
     * @var \CCDNComponent\AttachmentBundle\Manager\RegistryManager $registryManager
     */
    protected $registryManager;

    /**
     *
     * @access protected
     * @var Object $binSIUnitCalc
     */
    protected $binSIUnitCalc;

    /**
     *
     * @access protected
     * @var Object $fileManager
     */
    protected $fileManager;

    /**
     *
     * @access protected
     * @var int $attachmentsPerPageOnFolders
     */
    protected $attachmentsPerPageOnFolders;

    /**
     *
     * @access protected
     * @var int $quotaDiskSpace
     */
    protected $quotaDiskSpace;

    /**
     *
     * @access protected
     * @var int $quotaFileQuantity
     */
    protected $quotaFileQuantity;

    /**
     *
     * @access protected
     * @var int $quotaFileSize
     */
    protected $quotaFileSize;

    /**
     *
     * @access public
     * @param \Symfony\Component\DependencyInjection\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     *
     * @access public
     * @return \CCDNComponent\AttachmentBundle\Manager\AttachmentManager
     */
    public function getAttachmentManager()
    {
        if (null == $this->attachmentManager) {
            $this->attachmentManager = $this->container->get('ccdn_message_message.manager.envelope');
        }

        return $this->attachmentManager;
    }

    /**
     *
     * @access public
     * @return \CCDNComponent\AttachmentBundle\Manager\RegistryManager
     */
    public function getRegistryManager()
    {
        if (null == $this->registryManager) {
            $this->registryManager = $this->container->get('ccdn_message_message.manager.registry');
        }

        return $this->registryManager;
    }

    /**
     *
     * @access public
     * @return Object
     */
    public function getSIUnitCalc()
    {
        if (null == $this->binSIUnitCalc) {
            $this->binSIUnitCalc = $this->container->get('ccdn_component_common.component.helper.bin_si_units');
        }

        return $this->binSIUnitCalc;
    }

    /**
     *
     * @access public
     * @return Object
     */
    public function getFileManager()
    {
        if (null == $this->fileManager) {
            $this->fileManager = $this->container->get('ccdn_component_attachment.component.helper.file_manager');
        }

        return $this->fileManager;
    }

    /**
     *
     * @access public
     * @return int
     */
    public function getAttachmentsPerPageOnFolders()
    {
        if (null == $this->attachmentsPerPageOnFolders) {
            $this->attachmentsPerPageOnFolders = $this->container->getParameter('ccdn_component_attachment.manage.list.attachments_per_page');
        }

        return $this->attachmentsPerPageOnFolders;
    }

    /**
     *
     * @access public
     * @return int
     */
    public function getQuotaFileQuantity()
    {
        if (null == $this->quotaFileQuantity) {
            $this->quotaFileQuantity = $this->container->getParameter('ccdn_component_attachment.quota_per_user.max_files_quantity');
        }

        return $this->quotaFileQuantity;
    }

    /**
     *
     * @access public
     * @return int
     */
    public function getQuotaFileSize()
    {
        if (null == $this->quotaFileSize) {
            $this->quotaFileSize = $this->container->getParameter('ccdn_component_attachment.quota_per_user.max_filesize_per_file');
        }

        return $this->quotaFileSize;
    }

    /**
     *
     * @access public
     * @return int
     */
    public function getQuotaDiskSpace()
    {
        if (null == $this->quotaDiskSpace) {
            $this->quotaDiskSpace = $this->container->getParameter('ccdn_component_attachment.quota_per_user.max_total_quota');
        }

        return $this->quotaDiskSpace;
    }
}