<?php

namespace Mv\BlogBundle\Controller\AdminBlog;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Mv\BlogBundle\Entity\AdminBlog\Comment;
use Mv\BlogBundle\Form\AdminBlog\CommentType;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * AdminBlog\Comment controller.
 *
 * @Route("/badp/comment")
 */
class CommentController extends Controller
{
    /**
     * Lists all AdminBlog\Comment entities.
     *
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/", name="badp_comment")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MvBlogBundle:AdminBlog\Comment')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Displays a form to edit an existing AdminBlog\Comment entity.
     *
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{id}/edit", name="badp_comment_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MvBlogBundle:AdminBlog\Comment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AdminBlog\Comment entity.');
        }

        $editForm = $this->createForm(new CommentType('admin'), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing AdminBlog\Comment entity.
     *
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{id}/update", name="badp_comment_update")
     * @Method("POST")
     * @Template("MvBlogBundle:AdminBlog\Comment:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MvBlogBundle:AdminBlog\Comment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AdminBlog\Comment entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new CommentType('admin'), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', "Vos modifications ont été enregistrées.");

            return $this->redirect($this->generateUrl('badp_comment'));
        }
        $this->get('session')->getFlashBag()->add('error', "Il y a des erreurs dans le formulaire soumis !");

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a AdminBlog\Comment entity.
     *
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{id}/delete", name="badp_comment_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MvBlogBundle:AdminBlog\Comment')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find AdminBlog\Comment entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('badp_comment'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
