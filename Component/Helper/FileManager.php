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
     * @param \CCDNComponent\AttachmentBundle\Entity\Attachment   $attachment
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    public function saveFile(Attachment $attachment, $file)
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
            ->setCreatedDate(new \DateTime())
        ;

        return true;
    }

    /**
     *
     * @access public
     * @link http://www.php.net/manual/en/function.unlink.php
     */
    public function deleteFile(Attachment $attachment)
    {
        $file = realpath($this->fileStoreDir . $attachment->getPrivateKey());

        if (@unlink($file)) {
            return true;
        } else {
            return false;
        }
    }
}
