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

namespace CCDNComponent\AttachmentBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use FOS\UserBundle\Model\UserInterface;

/**
 * 
 * @author Reece Fowell <reece@codeconsortium.com> 
 * @version 1.0
 */
class RetrieverController extends ContainerAware
{
	
	
	/**
	 *
	 * @access public
	 * @param $attachment_id
	 * @return RedirectResponse|RenderResponse
	 */
	public function thumbnailAction($attachment_id)
	{
		$thumbnailLocation = $this->container->getParameter('kernel.root_dir') . '/../web/bundles/ccdncomponentcommon/images/icons/Black/32x32/';
		
		$fileRecord = $this->container->get('attachment.repository')->findOneById($attachment_id);

		$fileResolver = $this->container->get('attachment.file.resolver');
		
		$fileResolver->setThumbnailIconLocation($thumbnailLocation);
		$fileResolver->setFileName($fileRecord->getAttachmentOriginal());
		$fileResolver->setFileExtension($fileRecord->getFileExtension());
		
		if ($fileResolver->locateFile($fileRecord->getAttachment()))
		{
			if ( ! $fileResolver->loadFileData())
			{
				// mystery icon
				$fileResolver->setThumbnailUnknown();
			}
		} else {
			// mystery icon, do nothing.
			$fileResolver->setThumbnailUnknown();
		}
			
		return new Response(
				$fileResolver->getFileThumbnailData(),
				200,
				$fileResolver->getHTTPHeaders()
			);
	}



	/**
	 *
	 * @access public
	 * @param $attachment_id
	 * @return RedirectedResponse|RenderResponse
	 */
	public function downloadAction($attachment_id)
	{
		/*
		 *	Invalidate this action / redirect if user should not have access to it
		 */
		if ( ! $this->container->get('security.context')->isGranted('ROLE_USER')) {
			throw new AccessDeniedException('You do not have permission to use this resource!');
		}

		$user = $this->container->get('security.context')->getToken()->getUser();
		
		$thumbnailLocation = $this->container->getParameter('kernel.root_dir') . '/../web/bundles/ccdncomponentcommon/images/icons/Black/32x32/';
		
		$fileRecord = $this->container->get('attachment.repository')->findOneById($attachment_id);

		$fileResolver = $this->container->get('attachment.file.resolver');
		
		$fileResolver->setThumbnailIconLocation($thumbnailLocation);
		$fileResolver->setFileName($fileRecord->getAttachmentOriginal());
		$fileResolver->setFileExtension($fileRecord->getFileExtension());
		
		if ($fileResolver->locateFile($fileRecord->getAttachment()))
		{
			if ( ! $fileResolver->loadFileData())
			{
				throw new NotFoundHttpException('file data unable to be loaded.');
			}
		} else {
			throw new NotFoundHttpException('file was not located.');
		}
			
		return new Response(
				$fileResolver->getFileData(),
				200,
				$fileResolver->getHTTPHeaders()
			);			
	}
	
	
	
	/**
	 *
	 * @access protected
	 * @return string
	 */
	protected function getEngine()
    {
        return $this->container->getParameter('ccdn_component_attachment.template.engine');
    }



}