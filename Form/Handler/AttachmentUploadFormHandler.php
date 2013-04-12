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

namespace CCDNComponent\AttachmentBundle\Form\Handler;

//use Symfony\Component\Form\Form;
//use Symfony\Component\Form\FormFactory;
//use Symfony\Component\Form\FormError;
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\DependencyInjection\ContainerInterface;
//
//use CCDNComponent\AttachmentBundle\Manager\ManagerInterface;
//
//


use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

use CCDNComponent\AttachmentBundle\Manager\BaseManagerInterface;

use CCDNComponent\AttachmentBundle\Entity\Attachment;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class AttachmentUploadFormHandler
{
    /**
	 *
	 * @access protected
	 * @var \Symfony\Component\Form\FormFactory $factory
	 */
    protected $factory;
	
	/**
	 *
	 * @access protected
	 * @var \CCDNComponent\AttachmentBundle\Form\Type\AttachmentUploadFormType $attachmentUploadFormType
	 */
	protected $attachmentUploadFormType;
	
    /**
	 *
	 * @access protected
	 * @var \CCDNComponent\AttachmentBundle\Manager\BaseManagerInterface $manager
	 */
    protected $manager;

    /**
	 * 
	 * @access protected
	 * @var \CCDNComponent\AttachmentBundle\Form\Type\AttachmentUploadFormType $form 
	 */
    protected $form;
	
	/**
	 *
	 * @access protected
	 * @var \Symfony\Component\Security\Core\User\UserInterface $user
	 */
	protected $user;
	
    /**
     *
     * @access public
     * @param \Symfony\Component\Form\FormFactory $factory
	 * @param \CCDNComponent\AttachmentBundle\Form\Type\AttachmentUploadFormType $attachmentUploadFormType
	 * @param \CCDNComponent\AttachmentBundle\Manager\BaseManagerInterface $manager
	 * @param
     */
    public function __construct(FormFactory $factory, $attachmentUploadFormType, BaseManagerInterface $manager, $fileManager)
	{
        $this->factory = $factory;
		$this->attachmentUploadFormType = $attachmentUploadFormType;
        $this->manager = $manager;
		
		$this->fileManager = $fileManager;
    }
	
	/**
	 *
	 * @access public
	 * @param \Symfony\Component\Security\Core\User\UserInterface $user
	 * @return \CCDNComponent\AttachmentBundle\Form\Handler\AttachmentUploadFormHandler
	 */
	public function setUser(UserInterface $user)
	{
		$this->user = $user;
		
		return $this;
	}
	
    /**
     *
     * @access public
	 * @param \Symfony\Component\HttpFoundation\Request $request
     * @return bool
     */
    public function process(Request $request)
    {
        $this->getForm();

        if ($request->getMethod() == 'POST') {
            $this->form->bind($request);

            if ($this->form->isValid()) {
	            $formData = $this->form->getData();
				
				if ($this->getSubmitAction($request) == 'post') {
					
					if ($this->fileManager->saveFile($formData, $this->form['attachment']->getData(), $this->user)) {
		                $this->onSuccess($formData);

		                return true;
					} else {
						// Add form Error.
						return false;
					}
				}
            }
        }

        return false;
    }
	
	/**
	 *
	 * @access public
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @return string
	 */
	public function getSubmitAction(Request $request)
	{
		if ($request->request->has('submit')) {
			$action = key($request->request->get('submit'));
		} else {
			$action = 'post';
		}
		
		return $action;
	}
	
    /**
     *
     * @access public
     * @return Form
     */
    public function getForm()
    {
        if (! $this->form) {
            $this->form = $this->factory->create($this->attachmentUploadFormType);
        }

        return $this->form;
    }

    /**
     *
     * @access protected
     * @param \CCDNComponent\AttachmentBundle\Entity\Attachment $attachment
     * @return \CCDNComponent\AttachmentBundle\Manager\AttachmentManager
     */
    protected function onSuccess(Attachment $attachment)
    {
        return $this->manager->saveUpload($attachment)->flush();
    }
}
