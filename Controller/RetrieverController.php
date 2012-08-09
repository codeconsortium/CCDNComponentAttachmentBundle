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

namespace CCDNComponent\AttachmentBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
     * @param Int $attachmentId
     * @return RedirectResponse|RenderResponse
     */
    public function thumbnailAction($attachmentId)
    {
        $fileRecord = $this->container->get('ccdn_component_attachment.attachment.repository')->findOneById($attachmentId);

        $fileResolver = $this->container->get('ccdn_component_attachment.attachment.file.resolver');

        // What icon will we show if we cannot find what you are looking for?
        $thumbnailLocation = $this->container->getParameter('kernel.root_dir') . '/../web/bundles/ccdncomponentcommon/images/icons/Silver/32x32/';

        $fileResolver->setThumbnailIconLocationForUnresolvableFiles($thumbnailLocation);

        // Where are the attachment files stored?
        $fileResolver->setFileLocation($this->container->getParameter('ccdn_component_attachment.store.dir'));
        $fileResolver->setFileName($fileRecord->getFileNameHashed());
        $fileResolver->setFileExtension($fileRecord->getFileExtension());

        if ($fileResolver->locateFile()) {
            if ( ! $fileResolver->loadFileData()) {
                // mystery icon
                $fileResolver->useThumbnailIconTypeUnresolvable();
            }
        } else {
            // mystery icon, do nothing.
            $fileResolver->useThumbnailIconTypeUnresolvable();
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
     * @param Int $attachmentId
     * @return RedirectedResponse|RenderResponse
     */
    public function downloadAction($attachmentId)
    {
        //
        //	Invalidate this action / redirect if user should not have access to it
        //
        if ( ! $this->container->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException('You do not have permission to use this resource!');
        }

        $user = $this->container->get('security.context')->getToken()->getUser();

        $fileRecord = $this->container->get('ccdn_component_attachment.attachment.repository')->findOneById($attachmentId);

        $fileResolver = $this->container->get('ccdn_component_attachment.attachment.file.resolver');

        // What icon will we show if we cannot find what you are looking for?
        $thumbnailLocation = $this->container->getParameter('kernel.root_dir') . '/../web/bundles/ccdncomponentcommon/images/icons/Silver/32x32/';

        $fileResolver->setThumbnailIconLocationForUnresolvableFiles($thumbnailLocation);

        // Where are the attachment files stored?
        $fileResolver->setFileLocation($this->container->getParameter('ccdn_component_attachment.store.dir'));
        $fileResolver->setFileName($fileRecord->getFileNameHashed());
        $fileResolver->setFileExtension($fileRecord->getFileExtension());

        if ($fileResolver->locateFile()) {
            if ( ! $fileResolver->loadFileData()) {
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
     * @return String
     */
    protected function getEngine()
    {
        return $this->container->getParameter('ccdn_component_attachment.template.engine');
    }

}
