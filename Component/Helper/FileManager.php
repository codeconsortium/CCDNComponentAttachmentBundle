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

namespace CCDNComponent\AttachmentBundle\Component\Helper;

use Symfony\Component\Security\Core\User\UserInterface;

use CCDNComponent\AttachmentBundle\Entity\Attachment;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class FileManager
{
	/**
	 *
	 * @access protected
	 * @var Object $calc
	 */
    protected $calc;

	/**
	 *
	 * @access protected
	 * @var string $fileStoreDir
	 */
    protected $fileStoreDir;

	/**
	 *
	 * @access public
	 * @param Object $calc
	 * @param string $fileStoreDir
	 */
	public function __construct($calc, $fileStoreDir)
	{		
        // get the SI Units calculator.
        $this->calc = $calc;
		
        // Where do we keep the files after we are finished here?
        $this->fileStoreDir = $fileStoreDir;
	}
	
	/**
	 *
	 * @access public
	 * @param \CCDNComponent\AttachmentBundle\Entity\Attachment $attachment
	 * @param $file
	 * @param \Symfony\Component\Security\Core\User\UserInterface $user
	 */
	public function saveFile(Attachment $attachment, $file, UserInterface $owner)
	{
        // sort out the file meta-data.
        $fileNameOriginal = $file->getClientOriginalName();
        $fileNameHashed = md5(uniqid($fileNameOriginal . mt_rand(), true));
        $fileExtension = ($file->guessExtension()) ? $file->guessExtension() : 'bin';

        // shift the file out of tmp dir and into the filestore.
        $file->move($this->fileStoreDir, $fileNameHashed);

        // Get file size in bytes, we can convert it to an SIUnit in the formatter below.
        $fileSize = filesize($this->fileStoreDir . $fileNameHashed);
		
		$calc = $this->calc;
		
        // Complete remaining fields of entity.
        $attachment
			->setDescription(($attachment->getDescription()) ?: $fileNameOriginal)
	        ->setFileNameOriginal($fileNameOriginal)
	        ->setPublicKey($fileNameHashed)
	        ->setPrivateKey($fileNameHashed)
	        ->setFileExtension($fileExtension)
	        ->setFileSize($calc->formatToSIUnit($fileSize, $calc::KiB, true))
			->setOwnedByUser($owner)
			->setCreatedDate(new \DateTime())
		;
			
		return true;
	}
	
	/**
	 *
	 * @access public
	 */
	public function deleteFile()
	{
		return true;
	}
}
