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

namespace CCDNComponent\AttachmentBundle\Form\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.1
 *
 * @see http://symfony.com/doc/current/cookbook/validation/custom_constraint.html
 */
class UploadQuotaFileSize extends Constraint
{

    /**
     *
     * @access public
     */
    public $message = 'ccdn_component_attachment.form.validation_error.quota_file_size';

    /**
     *
     * @access public
     * @param $container, string $filename, string $size, string $limit
     */
    public function addFileTooBig($container, $filename, $size, $limit)
    {
        $this->message = $container->get('translator')->trans($this->message, array('%limit%' => $limit), 'CCDNComponentAttachmentBundle');
    }

    /**
     *
     * @access public
     * @return string
     */
    public function validatedBy()
    {
        return 'upload_quota_file_size';
    }

}
