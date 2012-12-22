<?php

namespace Mv\BlogBundle\Controller\AdminBlog;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Mv\BlogBundle\Entity\AdminBlog\Category;
use Mv\BlogBundle\Form\AdminBlog\CategoryType;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Blog\Category controller.
 *
 * @Route("/badp/category")
 */
class CategoryController extends Controller
{
    /**
     * Lists all Blog\Category entities.
     *
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/", name="badp_category")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MvBlogBundle:AdminBlog\Category')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Blog\Category entity.
     *
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/", name="badp_category_create")
     * @Method("POST")
     * @Template("MvBlogBundle:AdminBlog\Category:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Category();
        $form = $this->createForm(new CategoryType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('badp_category', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Blog\Category entity.
     *
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/new", name="badp_category_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Category();
        $form   = $this->createForm(new CategoryType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Blog\Category entity.
     *
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{id}/edit", name="badp_category_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MvBlogBundle:AdminBlog\Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Blog\Category entity.');
        }

        $editForm = $this->createForm(new CategoryType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Blog\Category entity.
     *
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{id}", name="badp_category_update")
     * @Method("PUT")
     * @Template("MvBlogBundle:AdminBlog\Category:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MvBlogBundle:AdminBlog\Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Blog\Category entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new CategoryType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('badp_category'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Blog\Category entity.
     *
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{id}", name="badp_category_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MvBlogBundle:AdminBlog\Category')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Blog\Category entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('badp_category'));
    }

    /**
     * Creates a form to delete a Blog\Category entity by id.
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
