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

use CCDNComponent\AttachmentBundle\Model\Attachment as AbstractAttachment;

class Attachment extends AbstractAttachment
{
    const ENDPOINT = 'attachment';

    /** @var integer $id */
    protected $id;

    /** @var \DateTime $createdDate */
    protected $createdDate;

    /** @var string $description */
    protected $description;

    /** @var string $filenameOriginal */
    protected $filenameOriginal;

    /** @var string $filenameHashed */
    protected $filenameHashed;

    /** @var string $fileExtension */
    protected $fileExtension;

    /** @var string $fileSize */
    protected $fileSize;

    /** @var File $attachment */
    protected $attachment;

	/**
	 *
	 * @access public
	 */
    public function __construct()
    {
        parent::__construct();
        // your own logic
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
     * @param $attachment
     * @return Attachment
     */
    public function setAttachment($attachment)
    {
        $this->attachment = $attachment;

        return $this;
    }

    /**
     * @return File
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

    /**
     * Set created_date
     *
     * @param datetime $createdDate
	 * @return Attachment
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
		
		return $this;
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
	 * @return Attachment
     */
    public function setDescription($description)
    {
        $this->description = $description;
		
		return $this;
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
	 * @return Attachment
     */
    public function setFilenameOriginal($filenameOriginal)
    {
        $this->filenameOriginal = $filenameOriginal;
		
		return $this;
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
	 * @return Attachment
     */
    public function setFilenameHashed($filenameHashed)
    {
        $this->filenameHashed = $filenameHashed;
		
		return $this;
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
	 * @return Attachment
     */
    public function setFileExtension($fileExtension)
    {
        $this->fileExtension = $fileExtension;
		
		return $this;
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
	 * @return Attachment
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;
		
		return $this;
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
}
