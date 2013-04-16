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

use CCDNComponent\AttachmentBundle\Entity\Folder;
use CCDNComponent\AttachmentBundle\Entity\Attachment;
use CCDNComponent\AttachmentBundle\Entity\Registry;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class RegistryManager extends BaseManager implements BaseManagerInterface
{

}