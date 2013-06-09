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

namespace CCDNComponent\AttachmentBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

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
class CCDNComponentAttachmentBundle extends Bundle
{
    /**
     *
     * @access public
     */
    public function boot()
    {
        $twig = $this->container->get('twig');
		
        $twig->addGlobal(
			'ccdn_component_attachment',
			array(
	            'seo' => array(
	                'title_length' => $this->container->getParameter('ccdn_component_attachment.seo.title_length'),
	            ),
	            'manage' => array(
	                'list' => array(
	                    'attachment_uploaded_datetime_format' => $this->container->getParameter('ccdn_component_attachment.manage.list.attachment_uploaded_datetime_format'),
	                    'layout_template' => $this->container->getParameter('ccdn_component_attachment.manage.list.layout_template'),
	                ),
	                'upload' => array(
	                    'layout_template' => $this->container->getParameter('ccdn_component_attachment.manage.upload.layout_template'),
	                    'form_theme' => $this->container->getParameter('ccdn_component_attachment.manage.upload.form_theme'),
	                ),
	            ),
	        )
		); // End Twig Globals.
    }
}
