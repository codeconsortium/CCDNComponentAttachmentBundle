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
class ManageController extends ContainerAware
{
	
	
	/**
	 *
	 * @access public
	 * @param $user_id, $page
	 * @return RedirectResponse|RenderResponse
	 */
	public function indexAction($page, $user_id)
	{

		if ( $user_id > 0)
		{
			if ( ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR'))
			{
				throw new AccessDeniedException('You do not have permission to access this resource!');
			} else {
				$user = $this->container->get('user.repository')->findOneById($user_id);			
				
				$crumb_trail = $this->container->get('crumb_trail')
					->add($this->container->get('translator')->trans('crumbs.attachments_index', array(), 'CCDNComponentAttachmentBundle'), 
						$this->container->get('router')->generate('cc_attachment_home_for_user', array('user_id' => $user_id)), "home");
			}
		} else {
			if ( ! $this->container->get('security.context')->isGranted('ROLE_USER'))
			{
				throw new AccessDeniedException('You do not have permission to access this resource!');
			} else {
				$user = $this->container->get('security.context')->getToken()->getUser();
				
				$crumb_trail = $this->container->get('crumb_trail')
					->add($this->container->get('translator')->trans('crumbs.attachment_index', array(), 'CCDNComponentAttachmentBundle'), 
						$this->container->get('router')->generate('cc_attachment_home'), "home");
			}
		}

		if ( ! is_object($user) || ! $user instanceof UserInterface)
		{
            throw new NotFoundHttpException('the user does not exist.');
        }
		
		$attachments_paginated = $this->container->get('attachment.repository')->findForUserById($user->getId());

		// deal with pagination.
		$attachments_per_page = 30; //$this->container->getParameter('ccdn_component_attachments.attachment.topics_per_page');

		$attachments_paginated->setMaxPerPage($attachments_per_page);
		$attachments_paginated->setCurrentPage($page, false, true);

		return $this->container->get('templating')->renderResponse('CCDNComponentAttachmentBundle:Attachment:list.html.' . $this->getEngine(), array(
			'user' => $user,
			'user_profile_route' => $this->container->getParameter('ccdn_component_attachment.user.profile_route'),
			'crumbs' => $crumb_trail,
			'attachments' => $attachments_paginated->getCurrentPageResults(),
			'pager' => $attachments_paginated,
			));
	}



	/**
	 *
	 * @access public
	 * @return RedirectedResponse|RenderResponse
	 */
	public function uploadAction()
	{
		/*
		 *	Invalidate this action / redirect if user should not have access to it
		 */
		if ( ! $this->container->get('security.context')->isGranted('ROLE_USER')) {
			throw new AccessDeniedException('You do not have permission to use this resource!');
		}
		
		$user = $this->container->get('security.context')->getToken()->getUser();
		
		$formHandler = $this->container->get('attachment.form.insert.handler')->setOptions(array('user' => $user));
			
		$form = $formHandler->getForm();
		
		if ($formHandler->process())	
		{	
			$this->container->get('session')->setFlash('notice', 
				$this->container->get('translator')->trans('flash.attachment.upload.success', array('%file_name%' => $form->getData()->getAttachmentOriginal()), 'CCDNComponentAttachmentBundle'));
										
			return new RedirectResponse($this->container->get('router')->generate('cc_attachment_home'));
		}
		else
		{
			// setup crumb trail.	
			$crumb_trail = $this->container->get('crumb_trail')
				->add($this->container->get('translator')->trans('crumbs.attachment_index', array(), 'CCDNComponentAttachmentBundle'), 
					$this->container->get('router')->generate('cc_attachment_home'), "home")
				->add($this->container->get('translator')->trans('crumbs.attachment_upload', array(), 'CCDNComponentAttachmentBundle'), 
					$this->container->get('router')->generate('cc_attachment_upload'), "publish");

			return $this->container->get('templating')->renderResponse('CCDNComponentAttachmentBundle:Attachment:upload.html.' . $this->getEngine(), array(
				'user_profile_route' => $this->container->getParameter('ccdn_component_attachment.user.profile_route'),
				'crumbs' => $crumb_trail,
				'form' => $form->createView(),
			));
		}
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