<?php

namespace Mv\BlogBundle\Controller\AdminBlog;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Mv\BlogBundle\Entity\AdminBlog\Post;
use Mv\BlogBundle\Form\AdminBlog\PostType;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Blog\Post controller.
 *
 * @Route("/badp/post")
 */
class PostController extends Controller
{
    /**
     * Lists all Blog\Post entities.
     *
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/", name="badp_post")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MvBlogBundle:AdminBlog\Post')->findAllOrdered();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $entities,
            $this->get('request')->query->get('page', 1)/*page number*/,
            $this->container->getParameter('mv_blog.max_per_page')/*limit per page*/
        );        
        
        return array(
            'pagination' => $pagination,
        );
    }

    /**
     * Creates a new Blog\Post entity.
     *
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/", name="badp_post_create")
     * @Method("POST")
     * @Template("MvBlogBundle:AdminBlog\Post:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $t = $this->get('translator');

        $entity  = new Post();
        $form = $this->createForm(new PostType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', $t->trans('admin.post.has_been_created'));

            return $this->redirect($this->generateUrl('badp_post_show', array('id' => $entity->getId())));
        }
        $this->get('session')->getFlashBag()->add('error', $t->trans('admin.form_submit_error'));

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Blog\Post entity.
     *
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/new", name="badp_post_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Post();
        $form   = $this->createForm(new PostType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Blog\Post entity.
     *
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{id}", name="badp_post_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MvBlogBundle:AdminBlog\Post')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Blog\Post entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Blog\Post entity.
     *
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{id}/edit", name="badp_post_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MvBlogBundle:AdminBlog\Post')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Blog\Post entity.');
        }

        $editForm = $this->createForm(new PostType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Blog\Post entity.
     *
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{id}", name="badp_post_update")
     * @Method("PUT")
     * @Template("MvBlogBundle:AdminBlog\Post:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $t = $this->get('translator');

        $entity = $em->getRepository('MvBlogBundle:AdminBlog\Post')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Blog\Post entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new PostType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', $t->trans('admin.form_submit_success'));

            return $this->redirect($this->generateUrl('badp_post_show', array('id' => $id)));
        }
        $this->get('session')->getFlashBag()->add('error', $t->trans('admin.form_submit_error'));

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Blog\Post entity.
     *
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{id}", name="badp_post_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MvBlogBundle:AdminBlog\Post')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Blog\Post entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('badp_post'));
    }

    /**
     * Creates a form to delete a Blog\Post entity by id.
     *
     * @Secure(roles="ROLE_ADMIN")
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
