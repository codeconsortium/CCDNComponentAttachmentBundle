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

namespace CCDNComponent\AttachmentBundle\Component;


class FileResolver
{
	
	protected $container;
	protected $fileRecord;
	protected $fileData;
	
	public function __construct($service_container)
	{
		$this->container = $service_container;
	}
	
	
	public function locateFile($fileRecord)
	{
		$this->fileRecord = $fileRecord;
		
		if (file_exists($fileRecord->getAttachment()))
		{
			return true;
		} else {
			return false;
		}
	}
	
	
	public function loadFileData()
	{
		ob_start();
		readfile($this->fileRecord->getAttachment());
		$this->fileData = ob_get_contents();
		
		if ( ! $this->fileData)
		{
			return false;
		} else {
			return true;
		}
	}
	
	public function getFileData()
	{
		return $this->fileData;
	}
	
	public function getHTTPHeaders()
	{
		switch($this->fileRecord->getFileExtension())
		{
			// graphics
/*			case "gif":
				$headerContentType = 'image/gif';
				break;
			case "jpg":
				$headerContentType = 'image/jpeg';
				break;
			case "jpeg":
				$headerContentType = 'image/jpeg';
				break;
			case "png":
				$headerContentType = 'image/png';
				break;
			case "svg":
				$headerContentType = 'image/svg+xml';
				break;
			case "tiff":
				$headerContentType = 'image/tiff';
				break;
			case "ico":
				$headerContentType = 'image/vnd.microsoft.icon';
				break;
			case "bmp":
				$headerContentType = 'image/x-ms-bmp';
				break;
			*/
			// archives + other
			default:
				$headerContentType = 'application/octet-stream';
				break;
		}
		
 		$fileSize = filesize($this->fileRecord->getAttachment());

		return array( 
			'Content-Description' => 'File Transfer',
			'Pragma' => 'public',
			'Expires' => 0,
			'Cache-Control' => 'must-revalidate', //', post-check=0, pre-check=0',	
			'Content-Type' => $headerContentType,
			'Content-Disposition' => sprintf('attachment; filename=%s', $this->fileRecord->getAttachmentOriginal()),
			'Content-Transfer-Encoding' => 'binary',
			'Content-Length' => $fileSize,
		);
	}
	
}