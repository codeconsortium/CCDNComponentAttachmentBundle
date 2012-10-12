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

namespace CCDNComponent\AttachmentBundle\Form\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.1
 *
 * @see http://symfony.com/doc/current/cookbook/validation/custom_constraint.html
 */
class UploadQuotaFileSizeValidator extends ConstraintValidator
{

    /**
     *
     * @access protected
     */
    protected $doctrine;

    /**
     *
     * @access protected
     */
    protected $container;

    /**
     *
     * @access public
     * @param $doctrine, $container
     */
    public function __construct($doctrine, $container)
    {
        $this->doctrine = $doctrine;

        $this->container = $container;
    }

    /**
     *
     * @access public
     * @param $file, Constraint $constraint
     * @return bool
     */
    public function isValid($file, Constraint $constraint)
    {
        if ($file) {
            // get the SI Units calculator.
            $calc = $this->container->get('ccdn_component_common.component.helper.bin_si_units');

            // check if the max_filesize_per_file_in_kb is reached
            $maxFileSizePerFile = $this->container->getParameter('ccdn_component_attachment.quota_per_user.max_filesize_per_file');

            $maxFileSizePerFileInKiB = $calc->formatToSIUnit($maxFileSizePerFile, $calc::KiB, false);

            // Get file.
            $fileNameOriginal = $file->getClientOriginalName();

            // Get file size in bytes, we can convert it to an SIUnit in the formatter below.
            $fileSize = $file->getClientSize();

            $fileSizeInKiB = $calc->formatToSIUnit($fileSize, $calc::KiB, false);

            if ($fileSizeInKiB > ($maxFileSizePerFileInKiB + 1)) {
                $constraint->addFileTooBig($this->container, $fileNameOriginal, $fileSizeInKiB, $maxFileSizePerFileInKiB);

                $this->setMessage($constraint->message);

                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

}
