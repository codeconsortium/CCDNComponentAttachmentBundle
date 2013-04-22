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

namespace CCDNComponent\AttachmentBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

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
abstract class Attachment
{
    /**
     *
     * @var UserInterface $ownedByUser
     */
    protected $ownedByUser;

    /**
     *
     * @access public
     */
    public function __construct()
    {
        // your own logic
    }

    /**
     * Get ownedByUser
     *
     * @return UserInterface
     */
    public function getOwnedByUser()
    {
        return $this->ownedByUser;
    }

    /**
     * Set ownedByUser
     *
     * @param  UserInterface $ownedByUser
     * @return Attachment
     */
    public function setOwnedByUser(UserInterface $ownedByUser)
    {
        $this->ownedByUser = $ownedByUser;

        return $this;
    }
}
