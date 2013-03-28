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

use CCDNComponent\AttachmentBundle\Controller\ManageBaseController;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class ManageController extends ManageBaseController
{
    /**
     *
     * @access public
     * @param int $page, int $userId
     * @return RedirectResponse|RenderResponse
     */
    public function indexAction($page, $userId)
    {
		$this->isAuthorised('ROLE_USER');

		$crumbs = $this->getCrumbs();
		
        if ($userId > 0) {
			$this->isAuthorised('ROLE_MODERATOR');

            $user = $this->container->get('ccdn_user_user.repository.user')->findOneById($userId);

            $crumbs->add($this->trans('crumbs.attachments_index'), $this->path('ccdn_component_attachment_index_for_user', array('userId' => $userId)));
        } else {
            $user = $this->container->get('security.context')->getToken()->getUser();

            $crumbs->add($this->trans('ccdn_component_attachment.crumbs.index'), $this->path('ccdn_component_attachment_index'));
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

        return $this->renderResponse('CCDNComponentAttachmentBundle:Manage:list.html.', array(
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
		$this->isAuthorised('ROLE_USER');

        $user = $this->getUser();

        //$formHandler = $this->container->get('ccdn_component_attachment.form.handler.attachment_upload')->setOptions(array('user' => $user));
		$formHandler = $this->getFormHandlerToUploadFiles($user);

        $form = $formHandler->getForm();

        if ($formHandler->process()) {
            $this->setFlash('success', $this->trans('ccdn_component_attachment.flash.attachment.upload.success', array('%file_name%' => $formHandler->getForm()->getData()->getFileNameOriginal())));

            return $this->redirectResponse($this->path('ccdn_component_attachment_index'));
        } else {
            $quotas = $this->container->get('ccdn_component_attachment.manager.attachment')->calculateQuotasForUser($user);

            // setup crumb trail.
            $crumbs = $this->getCrumbs()
                ->add($this->trans('ccdn_component_attachment.crumbs.index'), $this->path('ccdn_component_attachment_index'))
                ->add($this->trans('ccdn_component_attachment.crumbs.upload'), $this->path('ccdn_component_attachment_upload'));

            return $this->renderResponse('CCDNComponentAttachmentBundle:Manage:upload.html.', array(
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
		$this->isAuthorised('ROLE_USER');

        // get all the message id's
		$attachmentIds = $this->getCheckedItemIds('check_');
			
        if (count($attachmentIds) < 1) {
            return $this->redirectResponse($this->container->get('router')->generate('ccdn_component_attachment_index'));
        }

        $user = $this->getUser();

        $attachments = $this->container->get('ccdn_component_attachment.repository.attachment')->findTheseAttachmentsByUserId($attachmentIds, $user->getId());

        if (isset($_POST['submit_delete'])) {
            $this->container->get('ccdn_component_attachment.manager.attachment')->bulkDelete($attachments)->flush();
        }

        return $this->redirectResponse($this->path('ccdn_component_attachment_index'));
    }
}