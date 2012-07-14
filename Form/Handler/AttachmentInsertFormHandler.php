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
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;

use CCDNComponent\CommonBundle\Manager\ManagerInterface;

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
	 * @param FormFactory $factory, ContainerInterface $container, ManagerInterface $manager
	 */
	public function __construct(FormFactory $factory, ContainerInterface $container, ManagerInterface $manager)
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

			// get the UploadedFile instance
			$file = $this->form['attachment']->getData();
			
			// sort out the file meta-data
			$fileName = $file->getClientOriginalName();
			$fileExtension = $file->guessExtension();		
			if ( ! $fileExtension) { $fileExtension = 'bin'; }
			$fileNameHashed = md5(uniqid(mt_rand(), true));
			$fileStoreDir = $this->container->getParameter('ccdn_component_attachment.store.dir');	

			// shift the file out of tmp dir and into the filestore
			$file->move($fileStoreDir, $fileNameHashed);
			
			// get the records of all attachments for the user to do some work on.	
			$user = $this->container->get('security.context')->getToken()->getUser();
			$attachments = $this->container->get('ccdn_component_attachment.attachment.repository')->findForUserById($user->getId());

			// get the SI Units calculator
			$calc = $this->container->get('ccdn_component_common.bin.si.units');

			// get the file size in bytes
			$fileSize = filesize($fileStoreDir . $fileNameHashed);	
			
			// check file uploaded ok
			$this->validateUploadStatus($file);
			
			// validate quotas
			$this->validateMaxFileSize($calc, $fileSize);
			$this->validateTotalQuota($calc, $attachments);
            $this->validateMaxFileQuantity($attachments);
						
			// set the file properties for the db record.
			$formData->setCreatedDate(new \DateTime());
			$formData->setOwnedBy($this->options['user']);			
			$formData->setAttachmentOriginal($fileName);
			$formData->setAttachmentHashed($fileNameHashed);
			$formData->setFileExtension($fileExtension);
			$formData->setAttachment($fileStoreDir . $fileNameHashed);
			$formData->setFileSize($calc->formatToSIUnit($fileSize, null, true));
			
			// check form validation
			if ($this->form->isValid())
			{
				$this->onSuccess($this->form->getData());
							
				return true;				
			} else {
				@unlink($fileStoreDir . $fileNameHashed);
			}
			
		}

		return false;
	}
	
	
	
	/**
	 *
	 * @access protected
	 * @param string $file
	 */
	protected function validateUploadStatus($file)
	{
		if ( ! $file->isValid())
		{
			$this->form->addError(new FormError('Error while uploading the file.'));
			$this->form->addError(new FormError($file->getError()));
		}
	}
	
	
	
	/**
	 *
	 * @access protected
	 * @param Object $calc, string $fileSize
	 */
	protected function validateMaxFileSize($calc, $fileSize)
	{
		// check if the max_filesize_per_file_in_kb is reached
		$maxFileSizePerFile = $this->container->getParameter('ccdn_component_attachment.quota_per_user.max_filesize_per_file');		
		$maxFileSizePerFileInKiB = $calc->formatToSIUnit($maxFileSizePerFile, $calc::KiB, false);
		
		$fileSizeInKiB = $calc->formatToSIUnit($fileSize, $calc::KiB, false);
		
		if ($fileSizeInKiB > ($maxFileSizePerFileInKiB + 1))
		{
			// limit reached. reject this upload!
			$this->form->addError(new FormError('The file size limit is ' . $maxFileSizePerFileInKiB . 'KiB. The file you are trying to upload is ' . $fileSizeInKiB . 'KiB.'));
		}
		
	}
	
	
	
	/**
	 *
	 * @access protected
	 * @param Object $calc, Array() $attachments
	 */
	protected function validateTotalQuota($calc, $attachments)
	{
		// check if the max_total_quota_in_kb is reached
		$maxTotalQuota = $this->container->getParameter('ccdn_component_attachment.quota_per_user.max_total_quota');
		$maxTotalQuotaInKiB = $calc->formatToSIUnit($maxTotalQuota, $calc::KiB, false);
	
		// work out total used so far.
		$totalUsedSpaceInKiB = 0;

		foreach($attachments as $key => $attachment)
		{
			$totalUsedSpaceInKiB += $calc->formatToSIUnit($attachment->getFileSize(), $calc::KiB, false);
		}
		
		if ($totalUsedSpaceInKiB > $maxTotalQuotaInKiB)
		{
			// limit reached. reject this upload!
			$this->form->addError(new FormError('You have used up all (' . $maxTotalQuotaInKiB . 'KiB) of the allowed space (' . $totalUsedSpaceInKiB . 'KiB) in your attachments! Delete some attachments to free up room.'));
		}
	}
	
	
	
	/**
	 *
	 * @access protected
	 * @param Array() $attachments
	 */
	protected function validateMaxFileQuantity($attachments)
	{
		// check if the max_files_quantity is reached
		$maxFilesQuantity = $this->container->getParameter('ccdn_component_attachment.quota_per_user.max_files_quantity');
		
		if (count($attachments) >= $maxFilesQuantity)
		{
			// limit reached. reject this upload!
			$this->form->addError(new FormError("You have reached the maximum number of allowed files in your attachments!"));
		}
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
			$attachmentType = $this->container->get('ccdn_component_attachment.attachment.form.type');
				
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