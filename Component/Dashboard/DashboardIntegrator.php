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

namespace CCDNComponent\AttachmentBundle\Component\Dashboard;

use CCDNComponent\DashboardBundle\Component\Integrator\Model\BuilderInterface;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 2.0
 */
class DashboardIntegrator
{
    /**
	 * 
	 * @access public
     * @param CCDNComponent\DashboardBundle\Component\Integrator\Model\BuilderInterface $builder
     */
    public function build(BuilderInterface $builder)
    {
		$builder
			->addCategory('account')
				->setLabel('ccdn_component_attachment.dashboard.categories.account', array(), 'CCDNComponentAttachmentBundle')
				->addPages()
					->addPage('account')
						->setLabel('ccdn_component_attachment.dashboard.pages.account', array(), 'CCDNComponentAttachmentBundle')
					->end()
				->end()
				->addLinks()	
					->addLink('attachments')
						->setAuthRole('ROLE_USER')
						->setRoute('ccdn_component_attachment_index')
						->setIcon('/bundles/ccdncomponentcommon/images/icons/Black/32x32/32x32_attachment.png')
						->setLabel('ccdn_component_attachment.title.index', array(), 'CCDNComponentAttachmentBundle')
					->end()
				->end()
			->end()
		;
    }
}
