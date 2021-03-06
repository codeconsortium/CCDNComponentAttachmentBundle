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

namespace CCDNComponent\AttachmentBundle\Component\RouteRefererIgnore;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class RouteRefererIgnoreList
{

    /**
     *
 	 * @access public
	 * @return mixed[]
     */
    public function getRoutes()
    {
		$ignore = array(
			array('bundle' => 'ccdncomponentattachmentbundle', 'route' => 'ccdn_component_attachment_download'),
			array('bundle' => 'ccdncomponentattachmentbundle', 'route' => 'ccdn_component_attachment_thumbnail'),
		);

		return $ignore;
    }

}
