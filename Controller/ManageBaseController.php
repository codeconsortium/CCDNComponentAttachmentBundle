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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

use CCDNComponent\AttachmentBundle\Controller\BaseController;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class ManageBaseController extends BaseController
{
	/**
	 * 
	 * @access public
	 * @param \Symfony\Component\Security\Core\User\UserInterface $user
	 * @return \CCDNComponent\AttachmentBundle\Form\Handler\AttachmentInsertFormHandler
	 */
	public function getFormHandlerToUploadFiles(UserInterface $user)
	{
		$formHandler = $this->container->get('ccdn_component_attachment.form.handler.attachment_upload');
			
		$formHandler->setUser($user);
		
		return $formHandler;
	}
}