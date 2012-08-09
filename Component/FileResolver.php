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

namespace CCDNComponent\AttachmentBundle\Component;

class FileResolver
{

	/**
	 *
	 * @access protected
	 */
    protected $container;

	/**
	 *
	 * @access protected
	 */
    protected $thumbnailLocation;

	/**
	 *
	 * @access protected
	 */
    protected $fileLocation;

	/**
	 *
	 * @access protected
	 */
    protected $fileName;

	/**
	 *
	 * @access protected
	 */
    protected $fileExtension;

	/**
	 *
	 * @access protected
	 */
    protected $fileNameWithDir;

	/**
	 *
	 * @access protected
	 */
    protected $fileData;

	/**
	 *
	 * @access protected
	 */
    protected $isRenderable = false;

	/**
	 *
	 * @access protected
	 */
    protected $headerContentType;

	/**
	 *
	 * @access protected
	 */
    protected $fileSize;

    /**
     *
     * @access public
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     *
     * @access public
     * @param string $fileName
     */
    public function setFileLocation($dir)
    {
        $this->fileLocation = $dir;
    }

    /**
     *
     * @access public
     * @param string $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     *
     * @access public
     * @param string $fileExtension
     */
    public function setFileExtension($fileExtension)
    {
        $this->fileExtension = $fileExtension;
    }

    /**
     *
     * @access public
     * @param string $location
     */
    public function setThumbnailIconLocationForUnresolvableFiles($location)
    {
        $this->thumbnailLocation = $location;
    }

    /**
     *
     * @access public
     */
    public function useThumbnailIconTypeUnresolvable()
    {
        $this->fileNameWithDir = $this->thumbnailLocation . '32x32_attachment.png';
    }

    /**
     *
     * @access public
     * @param  string $file
     * @return bool
     */
    public function locateFile()
    {
        $this->fileNameWithDir = $this->fileLocation . $this->fileName;

        if (file_exists($this->fileNameWithDir)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @access public
     * @return bool
     */
    public function loadFileData()
    {
        ob_start();
        readfile($this->fileNameWithDir);
        $this->fileData = ob_get_clean();

        // get file size
        $this->fileSize = filesize($this->fileNameWithDir);

        if (! $this->fileData) {
            return false;
        } else {
            return true;
        }
    }

    /**
     *
     * @access public
     */
    public function resolveType()
    {

        switch ($this->fileExtension) {
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

    /**
     *
     * @access public
     * @return string $this->fileData
     */
    public function getFileData()
    {
        $this->resolveType();

        return $this->fileData;
    }

    /**
     *
     * @access public
     * @return string $this->fileData
     */
    public function getFileThumbnailData()
    {
        $this->resolveType();

        if (! $this->isRenderable) {
            $this->useThumbnailIconTypeUnresolvable();
        }

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
        $calc = $this->container->get('ccdn_component_common.bin.si.units');
        $fileSize = $calc->formatToSIUnit($this->fileSize, null, true);

        $textWidth = imagefontwidth(4)*strlen($fileSize);
        $tx = 2;//ceil($textWidth/2);
        $ty = 45;
        imagestring($tmp, 2, $tx, $ty, $fileSize, 0x00FFFFFF);

        // empty the image resource into binary string var we can send to the browser.
        ob_start();
        $stringDat = imagepng($tmp);
        $this->fileData = ob_get_clean();

        return $this->fileData;
    }

    /**
     *
     * @access public
     * @return Array()
     */
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
