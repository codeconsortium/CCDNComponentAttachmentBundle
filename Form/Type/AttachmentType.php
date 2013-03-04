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

namespace CCDNComponent\AttachmentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class AttachmentType extends AbstractType
{

    /**
     *
     * @access private
     */
    private $options;

    public function __construct()
    {
        $this->options = array();
    }

    /**
     *
     * @access public
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     *
     * @access public
     * @param FormBuilderInterface $builder, array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('attachment', 'file', array(
			'required' => true,
			'label' => 'ccdn_component_attachment.form.label.attachment.upload.file',
			'translation_domain' => 'CCDNComponentAttachmentBundle'
		));
        $builder->add('description', 'bb_editor', array(
			'label' => 'ccdn_component_attachment.form.label.attachment.upload.description',
			'translation_domain' => 'CCDNComponentAttachmentBundle'
        ));

    }

    /**
     *
     * for creating and replying to topics
     *
     * @access public
     * @param array $options
     */
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'CCDNComponent\AttachmentBundle\Entity\Attachment',
            'empty_data' => new \CCDNComponent\AttachmentBundle\Entity\Attachment(),
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'intention'       => 'attachment_item',
            'validation_groups' => 'upload',
        );
    }

    /**
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return 'Attachment';
    }

}
