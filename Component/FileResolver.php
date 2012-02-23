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

	protected $thumbnailLocation;	
	protected $fileNameWithDir;
	protected $fileName;
	protected $fileExtension;
	protected $fileData;
	protected $isRenderable = false;
	protected $headerContentType;
	protected $headerFileSize;

	public function __construct($service_container)
	{
		$this->container = $service_container;
	}
	
	public function setFileName($fileName)
	{
		$this->fileName = $fileName;
	}
	
	public function setFileExtension($fileExtension)
	{
		$this->fileExtension = $fileExtension;
	}
	
/*	public function setFileWithDir($fileNameWithDir)
	{
		$this->fileNameWithDir = $fileNameWithDir;
	}*/
	
	public function setThumbnailIconLocation($location)
	{
		$this->thumbnailLocation = $location;
	}
	
	public function setThumbnailUnknown()
	{
		$this->fileNameWithDir = realpath($this->thumbnailLocation . '32x32_attachment.png');
		$this->loadFileData();
	}	
	
	public function locateFile($file)
	{
		$this->fileNameWithDir = $file;
		
		if (file_exists($this->fileNameWithDir))
		{
			return true;
		} else {
			return false;
		}
	}
	
	public function loadFileData()
	{
		ob_start();
			readfile($this->fileNameWithDir);
		$this->fileData = ob_get_clean();
		
		if ( ! $this->fileData)
		{
			return false;
		} else {
			return true;
		}
	}

	public function resolveType()
	{
		$this->fileSize = filesize($this->fileNameWithDir);
 		
		switch($this->fileExtension)
		{
			// graphics
			case "gif":
				$this->headerContentType = 'image/gif';
				$this->isRenderable = true;
				break;
			case "jpg":
				$this->headerContentType = 'image/jpeg';
				$this->isRenderable = true;
				break;
			case "jpeg":
				$this->headerContentType = 'image/jpeg';
				$this->isRenderable = true;
				break;
			case "png":
				$this->headerContentType = 'image/png';
				$this->isRenderable = true;
				break;
			case "svg":
				$this->headerContentType = 'image/svg+xml';
				$this->isRenderable = false;
				break;
			case "tiff":
				$this->headerContentType = 'image/tiff';
				$this->isRenderable = false;
				break;
			case "ico":
				$this->headerContentType = 'image/vnd.microsoft.icon';
				$this->isRenderable = false;
				break;
			case "bmp":
				$this->headerContentType = 'image/x-ms-bmp';
				$this->isRenderable = false;
				break;
			
			// archives + other
			default:
				$this->headerContentType = 'application/octet-stream';
				break;
		}
		
	}
	
	
	public function getFileData()
	{
		$this->resolveType();
		return $this->fileData;
	}
	
	public function calcFileSize($bytes)
	{
		$fs = array('b', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB');
		
		//$size = number_format($bytes/pow(1024, $index=floor(log($bytes, 1024))), ($index >= 1) ? 2 : 0) . ' ' . $fs[$index];
		
		$bpow = floor(log($bytes, 1024));
		$size = round($bytes / pow(1024, $bpow), 1) . $fs[$bpow];
	
		return $size;
	}
	
	public function getFileThumbnailData()
	{
		$this->resolveType();
		
		if ( ! $this->isRenderable)
		{
			$this->setThumbnailUnknown();
		} else {

			// prep image resources
			$imgResource = imagecreatefromstring($this->fileData);
			$cx = imagesx($imgResource);
			$cy = imagesy($imgResource);		
			$nx = 60;
			$ny = 60;
			
			// create a new blank canvas
			$tmp = imagecreatetruecolor($nx, $ny);
			
			// copy the image, resize it and place it on the new canvas
			imagecopyresized($tmp, $imgResource, 0, 0, 0, 0, $nx, $ny - 15, $cx, $cy);
			
			// add a filesize stamp to bottom of thumbnail
			$fileSize = $this->calcFileSize($this->fileSize); //'15kb';
			$textWidth = imagefontwidth(4)*strlen($fileSize); 
			$tx = 2;//ceil($textWidth/2);
			$ty = 45;
			imagestring($tmp, 2, $tx, $ty, $fileSize, 0x00FFFFFF);
			
			// empty the image resource into binary string var we can send to the browser.
			ob_start();
				$stringDat = imagepng($tmp);
			$this->fileData = ob_get_clean();	
	
		}
		
		return $this->fileData;
	}
	
	public function getHTTPHeaders()
	{


		return array( 
			'Content-Description' => 'File Transfer',
			'Pragma' => 'cache, private',
			'Expires' => 0, // DateTime('D, d M Y H:i:s', time() + $offset) .' GMT',
			'Cache-Control' => 'post-check=0, pre-check=0, private',	
			'Content-Type' => $this->headerContentType,
			'Content-Disposition' => sprintf('attachment; filename=%s', $this->fileName),
			'Content-Transfer-Encoding' => 'binary',
			'Content-Length' => $this->fileSize,
		);
	}
	
}