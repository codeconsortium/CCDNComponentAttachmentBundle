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

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use CCDNComponent\AttachmentBundle\Controller\BaseController;
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
class RetrieverController extends BaseController
{
    /**
     *
     * @access private
     * @param  \CCDNComponent\AttachmentBundle\Entity\Attachment             $attachment
     * @return \CCDNComponent\AttachmentBundle\Component\Helper\FileResolver
     */
    private function getFileResolver(Attachment $attachment)
    {
        $fileResolver = $this->container->get('ccdn_component_attachment.component.helper.file_resolver');

        // What icon will we show if we cannot find what you are looking for?
        $thumbnailLocation = $this->container->getParameter('kernel.root_dir') . '/../web/bundles/ccdncomponentcommon/images/icons/Silver/32x32/';
        $fileResolver->setThumbnailIconLocationForUnresolvableFiles($thumbnailLocation);

        // Where are the attachment files stored?
        $fileResolver->setFileLocation($this->container->getParameter('ccdn_component_attachment.store.dir'));
        $fileResolver->setFileName($attachment->getPrivateKey());
        $fileResolver->setFileExtension($attachment->getFileExtension());

        if ($fileResolver->locateFile()) {
            if ( ! $fileResolver->loadFileData()) {
                // mystery icon
                $fileResolver->useThumbnailIconTypeUnresolvable();
            }
        } else {
            // mystery icon, do nothing.
            $fileResolver->useThumbnailIconTypeUnresolvable();
        }

        return $fileResolver;
    }

    /**
     *
     * @access public
     * @param  int                             $publicKey
     * @return RedirectResponse|RenderResponse
     */
    public function thumbnailAction($scale, $publicKey)
    {
        $attachment = $this->getAttachmentManager()->findOneAttachmentByPublicKey($publicKey);

        $fileResolver = $this->getFileResolver($attachment);

        return new Response(
            $fileResolver->getFileThumbnailData(),
            200,
            $fileResolver->getHTTPHeaders()
        );
    }

    /**
     *
     * @access public
     * @param  int                               $publicKey
     * @return RedirectedResponse|RenderResponse
     */
    public function downloadAction($publicKey)
    {
        $this->isAuthorised('ROLE_USER');

        $attachment = $this->getAttachmentManager()->findOneAttachmentByPublicKey($publicKey);

        $fileResolver = $this->getFileResolver($attachment);

        $this->isFound($fileResolver->locateFile(), 'file data unable to be loaded.');
        $this->isFound($fileResolver->loadFileData(), 'file was not located.');

        return new Response(
            $fileResolver->getFileData(),
            200,
            $fileResolver->getHTTPHeaders()
        );
    }
}
