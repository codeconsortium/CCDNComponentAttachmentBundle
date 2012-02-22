<?php

/*
 * This file is part of the CCDN AttachmentBundle
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
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="CCDNComponent\AttachmentBundle\Repository\AttachmentRepository")
 * @ORM\Table(name="CC_Component_Attachment")
 */
class Attachment
{

	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	protected $id;

	/**
     * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank
     */
	protected $attachment;
	
	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */	
	protected $attachment_original;
	
	/**
     * @ORM\Column(type="string", length=100)
     */
	protected $attachment_hashed;
	
	/**
	 * @ORM\Column(type="string", length=30)
	 */
	protected $file_extension;
	
	/**
     * @ORM\Column(type="text")
	 * @Assert\NotBlank(message = "Description must not be blank!")
     */
	protected $description;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $created_date;
	
	/**
     * @ORM\ManyToOne(targetEntity="CCDNUser\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="created_by_user_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $created_by;




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
     * Set created_date
     *
     * @param datetime $createdDate
     * @return Attachment
     */
    public function setCreatedDate($createdDate)
    {
        $this->created_date = $createdDate;
        return $this;
    }

    /**
     * Get created_date
     *
     * @return datetime 
     */
    public function getCreatedDate()
    {
        return $this->created_date;
    }

    /**
     * Set created_by
     *
     * @param CCDNUser\UserBundle\Entity\User $createdBy
     * @return Attachment
     */
    public function setCreatedBy(\CCDNUser\UserBundle\Entity\User $createdBy = null)
    {
        $this->created_by = $createdBy;
        return $this;
    }

    /**
     * Get created_by
     *
     * @return CCDNUser\UserBundle\Entity\User 
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * Set file_extension
     *
     * @param string $fileExtension
     * @return Attachment
     */
    public function setFileExtension($fileExtension)
    {
        $this->file_extension = $fileExtension;
        return $this;
    }

    /**
     * Get file_extension
     *
     * @return string 
     */
    public function getFileExtension()
    {
        return $this->file_extension;
    }

    /**
     * Set attachment
     *
     * @param string $attachment
     * @return Attachment
     */
    public function setAttachment($attachment)
    {
        $this->attachment = $attachment;
        return $this;
    }

    /**
     * Get attachment
     *
     * @return string 
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

    /**
     * Set attachment_hashed
     *
     * @param string $attachmentHashed
     * @return Attachment
     */
    public function setAttachmentHashed($attachmentHashed)
    {
        $this->attachment_hashed = $attachmentHashed;
        return $this;
    }

    /**
     * Get attachment_hashed
     *
     * @return string 
     */
    public function getAttachmentHashed()
    {
        return $this->attachment_hashed;
    }

    /**
     * Set attachment_original
     *
     * @param string $attachmentOriginal
     * @return Attachment
     */
    public function setAttachmentOriginal($attachmentOriginal)
    {
        $this->attachment_original = $attachmentOriginal;
        return $this;
    }

    /**
     * Get attachment_original
     *
     * @return string 
     */
    public function getAttachmentOriginal()
    {
        return $this->attachment_original;
    }
}