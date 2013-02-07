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
     * @param int $page, int $userId
     * @return RedirectResponse|RenderResponse
     */
    public function indexAction($page, $userId)
    {

        if ( ! $this->container->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException('You do not have permission to access this resource!');
        }

        if ($userId > 0) {
            if ( ! $this->container->get('security.context')->isGranted('ROLE_MODERATOR')) {
                throw new AccessDeniedException('You do not have permission to access this resource!');
            }

            $user = $this->container->get('ccdn_user_user.repository.user')->findOneById($userId);

            $crumbs = $this->container->get('ccdn_component_crumb.trail')
                ->add($this->container->get('translator')->trans('crumbs.attachments_index', array(), 'CCDNComponentAttachmentBundle'),
                    $this->container->get('router')->generate('ccdn_component_attachment_index_for_user', array('userId' => $userId)), "home");
        } else {
            $user = $this->container->get('security.context')->getToken()->getUser();

            $crumbs = $this->container->get('ccdn_component_crumb.trail')
                ->add($this->container->get('translator')->trans('ccdn_component_attachment.crumbs.index', array(), 'CCDNComponentAttachmentBundle'),
                    $this->container->get('router')->generate('ccdn_component_attachment_index'), "home");
        }

        if ( ! is_object($user) || ! $user instanceof UserInterface) {
            throw new NotFoundHttpException('the user does not exist.');
        }

        $quotas = $this->container->get('ccdn_component_attachment.manager.attachment')->calculateQuotasForUser($user);

        $attachmentsPager = $this->container->get('ccdn_component_attachment.repository.attachment')->findForUserById($user->getId());

        // deal with pagination.
        $attachmentsPerPage = $this->container->getParameter('ccdn_component_attachment.manage.list.attachments_per_page');

        $attachmentsPager->setMaxPerPage($attachmentsPerPage);
        $attachmentsPager->setCurrentPage($page, false, true);

        return $this->container->get('templating')->renderResponse('CCDNComponentAttachmentBundle:Manage:list.html.' . $this->getEngine(), array(
            'user' => $user,
            'crumbs' => $crumbs,
            'attachments' => $attachmentsPager->getCurrentPageResults(),
            'pager' => $attachmentsPager,
            'quotas' => $quotas,
		));
    }

    /**
     *
     * @access public
     * @return RedirectedResponse|RenderResponse
     */
    public function uploadAction()
    {
        //
        //	Invalidate this action / redirect if user should not have access to it
        //
        if ( ! $this->container->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException('You do not have permission to use this resource!');
        }

        $user = $this->container->get('security.context')->getToken()->getUser();

        $formHandler = $this->container->get('ccdn_component_attachment.form.handler.attachment_upload')->setOptions(array('user' => $user));

        $form = $formHandler->getForm();

        if ($formHandler->process()) {
            $this->container->get('session')->setFlash('success',
                $this->container->get('translator')->trans('ccdn_component_attachment.flash.attachment.upload.success', array('%file_name%' => $formHandler->getForm()->getData()->getFileNameOriginal()), 'CCDNComponentAttachmentBundle'));

            return new RedirectResponse($this->container->get('router')->generate('ccdn_component_attachment_index'));
        } else {
            $quotas = $this->container->get('ccdn_component_attachment.manager.attachment')->calculateQuotasForUser($user);

            // setup crumb trail.
            $crumbs = $this->container->get('ccdn_component_crumb.trail')
                ->add($this->container->get('translator')->trans('ccdn_component_attachment.crumbs.index', array(), 'CCDNComponentAttachmentBundle'),
                    $this->container->get('router')->generate('ccdn_component_attachment_index'), "home")
                ->add($this->container->get('translator')->trans('ccdn_component_attachment.crumbs.upload', array(), 'CCDNComponentAttachmentBundle'),
                    $this->container->get('router')->generate('ccdn_component_attachment_upload'), "publish");

            return $this->container->get('templating')->renderResponse('CCDNComponentAttachmentBundle:Manage:upload.html.' . $this->getEngine(), array(
                'crumbs' => $crumbs,
                'form' => $form->createView(),
                'quotas' => $quotas,
            ));
        }
    }

    /**
     *
     * @access public
     * @return RedirectResponse
     */
    public function bulkAction()
    {
        if ( ! $this->container->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException('You do not have access to this section.');
        }

        // get all the message id's
        $objectIds = array();
        $ids = $_POST;
        foreach ($ids as $objectKey => $objectId) {
            if (substr($objectKey, 0, 18) == 'check_multiselect_') {
                $id = (int) substr($objectKey, 18, (strlen($objectKey) - 18));

                if (is_int($id) == true) {
                    $objectIds[] = $id;
                }
            }
        }

        if (count($objectIds) < 1) {
            return new RedirectResponse($this->container->get('router')->generate('ccdn_component_attachment_index'));
        }

        $user = $this->container->get('security.context')->getToken()->getUser();

        $attachments = $this->container->get('ccdn_component_attachment.repository.attachment')->findTheseAttachmentsByUserId($objectIds, $user->getId());

        if (isset($_POST['submit_delete'])) {
            $this->container->get('ccdn_component_attachment.manager.attachment')->bulkDelete($attachments)->flush();
        }

        return new RedirectResponse($this->container->get('router')->generate('ccdn_component_attachment_index'));
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
