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

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;

use CCDNComponent\AttachmentBundle\Manager\ManagerInterface;

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
     * @param array $options
     * @return self
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

        if ($this->request->getMethod() == 'POST') {
            $this->form->bindRequest($this->request);

            $formData = $this->form->getData();

            // set the file properties for the db record.
            $formData->setCreatedDate(new \DateTime());
            $formData->setOwnedBy($this->options['user']);

            // check form validation.
            if ($this->form->isValid()) {
                // get the SI Units calculator.
                $calc = $this->container->get('ccdn_component_common.component.helper.bin_si_units');

                // Where do we keep the files after we are finished here?
                $fileStoreDir = $this->container->getParameter('ccdn_component_attachment.store.dir');

                // get the UploadedFile instance.
                $file = $this->form['attachment']->getData();

                // sort out the file meta-data.
                $fileNameOriginal = $file->getClientOriginalName();
                $fileNameHashed = md5(uniqid(mt_rand(), true));
                $fileExtension = ($file->guessExtension()) ? $file->guessExtension() : 'bin';

                // shift the file out of tmp dir and into the filestore.
                $file->move($fileStoreDir, $fileNameHashed);

                // Get file size in bytes, we can convert it to an SIUnit in the formatter below.
                $fileSize = filesize($fileStoreDir . $fileNameHashed);

                // Complete remaining fields of entity.
                $formData->setDescription(($formData->getDescription()) ? $formData->getDescription(): $fileNameOriginal);
                $formData->setFileNameOriginal($fileNameOriginal);
                $formData->setFileNameHashed($fileNameHashed);
                $formData->setFileExtension($fileExtension);
                $formData->setFileSize($calc->formatToSIUnit($fileSize, null, true));

                $this->onSuccess($this->form->getData());

                return true;
            }
        }

        return false;
    }

    /**
     *
     * @access public
     * @return Form $form
     */
    public function getForm()
    {
        if (! $this->form) {
            $attachmentType = $this->container->get('ccdn_component_attachment.form.type.attachment');

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
        return $this->manager->insert($entity)->flush();
    }

}
