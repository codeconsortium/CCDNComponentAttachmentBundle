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

namespace CCDNComponent\AttachmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CCDNComponent\AttachmentBundle\Repository\AttachmentRepository")
 */
class Attachment
{

    /**
	 * @var int $id
     */
    protected $id;

    /**
	 * @var User $ownedBy
     */
    protected $ownedBy;

    /**
     * @var \DateTime $createdDate
     */
    protected $createdDate;

    /**
     * @var string $description
     */
    protected $description;

    /**
     * @var string $filenameOriginal
     */
    protected $filenameOriginal;

    /**
     * @var string $filenameHashed
     */
    protected $filenameHashed;

    /**
     * @var string $fileExtension
     */
    protected $fileExtension;

    /**
     * @var string $fileSize
     */
    protected $fileSize;

    /**
     * @var File $attachment
     */
    protected $attachment;

    public function setAttachment($attachment)
    {
        $this->attachment = $attachment;
    }

    public function getAttachment()
    {
        return $this->attachment;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set created_date
     *
     * @param datetime $createdDate
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * Get created_date
     *
     * @return datetime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set filenameOriginal
     *
     * @param string $filenameOriginal
     */
    public function setFilenameOriginal($filenameOriginal)
    {
        $this->filenameOriginal = $filenameOriginal;
    }

    /**
     * Get filenameOriginal
     *
     * @return string
     */
    public function getFilenameOriginal()
    {
        return $this->filenameOriginal;
    }

    /**
     * Set filenameHashed
     *
     * @param string $filenameHashed
     */
    public function setFilenameHashed($filenameHashed)
    {
        $this->filenameHashed = $filenameHashed;
    }

    /**
     * Get filenameHashed
     *
     * @return string
     */
    public function getFilenameHashed()
    {
        return $this->filenameHashed;
    }

    /**
     * Set fileExtension
     *
     * @param string $fileExtension
     */
    public function setFileExtension($fileExtension)
    {
        $this->fileExtension = $fileExtension;
    }

    /**
     * Get fileExtension
     *
     * @return string
     */
    public function getFileExtension()
    {
        return $this->fileExtension;
    }

    /**
     * Set fileSize
     *
     * @param text $fileSize
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;
    }

    /**
     * Get fileSize
     *
     * @return text
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * Set ownedBy
     *
     * @param CCDNUser\UserBundle\Entity\User $ownedBy
     */
    public function setOwnedBy(\CCDNUser\UserBundle\Entity\User $ownedBy)
    {
        $this->ownedBy = $ownedBy;
    }

    /**
     * Get ownedBy
     *
     * @return CCDNUser\UserBundle\Entity\User
     */
    public function getOwnedBy()
    {
        return $this->ownedBy;
    }
}
