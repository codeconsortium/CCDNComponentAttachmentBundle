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

abstract class Attachment
{
    /** @var UserInterface $ownedBy */
    protected $ownedBy;
	
	/**
	 *
	 * @access public
	 */
    public function __construct()
    {
        // your own logic
    }

    /**
     * Get ownedBy
     *
     * @return UserInterface
     */
    public function getOwnedBy()
    {
        return $this->ownedBy;
    }
	
    /**
     * Set ownedBy
     *
     * @param UserInterface $ownedBy
	 * @return Attachment
     */
    public function setOwnedBy(UserInterface $ownedBy)
    {
        $this->ownedBy = $ownedBy;
		
		return $this;
    }
}
