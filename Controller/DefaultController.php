<?php

namespace Mv\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Mv\BlogBundle\Entity\AdminBlog\Category;
use Mv\BlogBundle\Entity\AdminBlog\Post;
use Mv\BlogBundle\Entity\AdminBlog\Comment;
use Mv\BlogBundle\Form\AdminBlog\CommentType;
use Doctrine\Common\Collections\ArrayCollection;
use Mv\BlogBundle\Entity\AdminBlog\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * DefaultController controller.
 *
 * @Route("/blog")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="blog_homepage")
     * @Template()
     */
    public function homepageAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MvBlogBundle:AdminBlog\Post')->findAllPubliedOrdered();
        
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
     * @Route("/category/{id}", name="blog_category_list")
     * @Template()
     */
    public function categoryAction(Category $category)
    {        
        $entities = new ArrayCollection( $category->getPosts()->toArray() );
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $entities->matching(PostRepository::getPubliedOrderedCriteria()),
            $this->get('request')->query->get('page', 1)/*page number*/,
            $this->container->getParameter('mv_blog.max_per_page')/*limit per page*/
        );
        
        return array(
            'category' => $category,
            'pagination' => $pagination,
        );
    }
    
    /**
     * @Template()
     */
    public function menuAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MvBlogBundle:AdminBlog\Category')->getRootNodes('title');
        
        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Blog\Post entity.
     *
     * @Route("/article/{id}", name="blog_post_show")
     * @Method("GET")
     * @Template()
     */
    public function showArticleAction(Post $entity)
    {
        $comment = new Comment();
        $comment->setIp($this->getRequest()->getClientIp());
        $form   = $this->createForm(new CommentType(), $comment);
        
        return array(
            'entity'      => $entity,
            'form'        => $form->createView()
        );
    }

    /**
     *
     * @Route("/com-article/{id}", name="blog_post_comment")
     * @Method("POST")
     * @Template("MvBlogBundle:Default:showArticle.html.twig")
     */
    public function addCommentAction(Post $entity, Request $request){
        $comment  = new Comment();
        $comment->setPost($entity);
        $form = $this->createForm(new CommentType(), $comment);
        $form->bind($request);
        
        $comment->setToken($this->getRequest()->server->get('UNIQUE_ID') . date('U'));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            
            $message = \Swift_Message::newInstance()
                ->setSubject('Publication de votre commentaire')
                ->setFrom($this->container->getParameter('mv_blog.robot_email'))
                ->setTo($comment->getEmail())
                ->setBody($this->renderView('MvBlogBundle:Default/Mail:confirm-comment.txt.twig',
                                            array(  'message'       => $comment->getComment(),
                                                    'url_article'   => $this->generateUrl('blog_post_show', array('id' => $entity->getId()),true),
                                                    'url'           => $this->generateUrl('blog_post_comment_confirm', array('email' => $comment->getEmail(), 'token' => $comment->getToken()), true))))
            ;
            $this->get('mailer')->send($message);
            
            $this->get('session')->getFlashBag()->add('notice', "Votre commentaire est enregistré.");
            $this->get('session')->getFlashBag()->add('notice', "Vous allez recevoir un message vous demandant de confirmer sa publication.");
    
            return $this->redirect($this->generateUrl('blog_post_show', array('id' => $entity->getId())));
        }
        
        return array(
            'entity'      => $entity,
            'form'        => $form->createView()
        );
    }

    /**
     *
     * @Route("/com-confirm/{email}/{token}", name="blog_post_comment_confirm")
     * @Method("GET")
     * @Template()
     */
    public function commentConfirmAction($email, $token){
        
        $em = $this->getDoctrine()->getManager();

        $comment = $em->getRepository('MvBlogBundle:AdminBlog\Comment')->findOneBy(array('email' => $email,'token' => $token));
        
        if($comment instanceof Comment){
            if($comment->getPublied() === null){
                $comment->setPublied(new \DateTime('now'));
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', "Votre commentaire est confirmé !");
            }else
                $this->get('session')->getFlashBag()->add('error', "Votre commentaire a déjà été confirmé !");
                
            return $this->redirect($this->generateUrl('blog_post_show', array('id' => $comment->getPost()->getId())));
        }
        
        throw new HttpException(401,'Unauthorized access.');
    }
}
