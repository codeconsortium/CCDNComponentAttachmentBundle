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

namespace CCDNComponent\AttachmentBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;

use CCDNComponent\CommonBundle\Entity\Manager\EntityManagerInterface;

/**
 * 
 * @author Reece Fowell <reece@codeconsortium.com> 
 * @version 1.0
 */
class AttachmentInsertFormHandler
{
	
	
	/**
	 *
	 * @access protected
	 */	
	protected $factory;
	
	
	/**
	 *
	 * @access protected
	 */
	protected $container;
	
	
	/**
	 *
	 * @access protected
	 */
	protected $request;
	
	
	/**
	 *
	 * @access protected
	 */
	protected $manager;
	
	
	/**
	 *
	 * @access protected
	 */
	protected $options;
	
	
	/**
	 *
	 * @access protected
	 */
	protected $form;


	/**
	 *
	 * @access public
	 * @param FormFactory $factory, ContainerInterface $container, EntityManagerInterface $manager
	 */
	public function __construct(FormFactory $factory, ContainerInterface $container, EntityManagerInterface $manager)
	{
		$this->options = array();
		$this->factory = $factory;
		$this->container = $container;
		$this->manager = $manager;

		$this->request = $container->get('request');
	}
	
	
	/**
	 *
	 * @access public
	 * @param Array() $options
	 * @return $this
	 */
	public function setOptions(array $options = null )
	{
		$this->options = $options;
		
		return $this;
	}
	
	
	/**
	 *
	 * @access public
	 * @return bool
	 */
	public function process()
	{		
		$this->getForm();
			
		if ($this->request->getMethod() == 'POST')
		{
			$this->form->bindRequest($this->request);
		
			$formData = $this->form->getData();

			// sort out the file details
			$dir = $this->container->getParameter('ccdn_component_attachment.store.dir');
			$file = $this->form['attachment']->getData();
			
			$fileName = $file->getClientOriginalName();
			$fileExtension = $file->guessExtension();
			
			
			$fileNameHashed = md5(uniqid(mt_rand(), true));
			
			if ( ! $fileExtension)
			{
				$fileExtension = 'bin';
			}
			
			
			// set the file properties in the db
			$formData->setCreatedDate(new \DateTime());
			$formData->setOwnedBy($this->options['user']);
			
			$formData->setAttachmentOriginal($fileName);
			$formData->setAttachmentHashed($fileNameHashed);
			$formData->setFileExtension($fileExtension);

			if ($this->form->isValid())
			{				
				$this->form['attachment']->getData()->move($dir, $fileNameHashed);
				$formData->setAttachment($dir . $fileNameHashed);
		
				//$resolver = $this->container->get('attachment.file.resolver');
				//$resolver->setFileName($fileRecord->getAttachmentOriginal());
				//$fileSize = $resolver->calcFileSize($fileRecord->getAttachment());
				
				$formData->setFileSize(filesize($fileSize));
				
				$this->onSuccess($this->form->getData());
							
				return true;				
			}
			
		}

		return false;
	}
	
	
	/**
	 *
	 * @access public
	 * @return Form
	 */
	public function getForm()
	{
		if ( ! $this->form)
		{
			$attachmentType = $this->container->get('attachment.form.type');
				
			$this->form = $this->factory->create($attachmentType);			
		}

		return $this->form;
	}
	
	
	/**
	 *
	 * @access protected
	 * @param $entity
	 * @return PostManager
	 */
	protected function onSuccess($entity)
    {
		return $this->manager->insert($entity)->flushNow();
    }

}