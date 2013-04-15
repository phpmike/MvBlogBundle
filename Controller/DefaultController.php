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
     * @Route("/{id}-{slug}/", requirements={"slug" = "[a-z0-9\-]+", "id" = "^\d+$" }, name="blog_category_list")
     * @Route("/category/{id}")
     * @Template()
     */
    public function categoryAction(Category $category)
    {
        if($this->get('request')->get('slug') != $category->getSlug())
            return $this->redirect($this->generateUrl('blog_category_list', $category->getRoutingParams()));
        
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
            'home_link' => $this->container->getParameter('mv_blog.menu_display_accueil'),
        );
    }

    /**
     * Finds and displays a Blog\Post entity.
     *
     * @Route("/{category_id}-{category}/{id}-{slug}.html", requirements={"slug" = "[a-z0-9\-]+", "category" = "[a-z0-9\-]+", "id" = "^\d+$", "category_id" = "^\d+$" }, name="blog_post_show")
     * @Route("/article/{id}")
     * @Method("GET")
     * @Template()
     */
    public function showArticleAction(Post $post)
    {
        $routing_params = $post->getRoutingParams();
        unset($routing_params['id']);
        foreach ($routing_params AS $key => $value)
            if($this->get('request')->get($key) != $value)
                return $this->redirect($this->generateUrl('blog_post_show', $post->getRoutingParams()));
        
        $comment = new Comment();
        $comment->setIp($this->getRequest()->getClientIp());
        $form   = $this->createForm(new CommentType(), $comment);
        
        return array(
            'entity'            => $post,
            'form'              => $form->createView(),
            'facebook_api_id'   => $this->container->getParameter('mv_blog.facebook_api_id')
        );
    }

    /**
     *
     * @Route("/com-article/{id}", name="blog_post_comment")
     * @Method("POST")
     * @Template("MvBlogBundle:Default:showArticle.html.twig")
     */
    public function addCommentAction(Post $entity, Request $request){
        // Si le précédent commentaire est trop récent, on vire le client !
        $last_comment = $this->getDoctrine()->getManager()->getRepository('MvBlogBundle:AdminBlog\Comment')->findOneByIpLast( $this->getRequest()->getClientIp() );
        if($last_comment && (date('U') - $last_comment->getCreated()->format('U') < $this->container->getParameter('mv_blog.min_elapsed_time_comment')))
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException(); // changer peut-être par quelque chose de plus ortodoxe...
        
        $comment  = new Comment();
        $comment->setPost($entity);
        $form = $this->createForm(new CommentType(), $comment);
        $form->bind($request);
        
        $comment->setToken($this->getRequest()->server->get('UNIQUE_ID') . date('U'));

        /** @var $t \Symfony\Bundle\FrameworkBundle\Translation\Translator */
        $t = $this->get('translator');

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            
            $message = \Swift_Message::newInstance()
                ->setSubject($t->trans('default.comment.email.subject', array(), 'MvBlogBundle'))
                ->setFrom($this->container->getParameter('mv_blog.robot_email'))
                ->setTo($comment->getEmail())
                ->setBody($this->renderView('MvBlogBundle:Default/Mail:confirm-comment.txt.twig',
                                            array(  'message'       => $comment->getComment(),
                                                    'url_article'   => $this->generateUrl('blog_post_show', $entity->getRoutingParams(),true),
                                                    'url'           => $this->generateUrl('blog_post_comment_confirm', array('email' => $comment->getEmail(), 'token' => $comment->getToken()), true))))
            ;
            $this->get('mailer')->send($message);

            $this->get('session')->getFlashBag()->add('notice', $t->trans('default.comment.saved', array(), 'MvBlogBundle'));
            $this->get('session')->getFlashBag()->add('notice', $t->trans('default.comment.message_notice', array(), 'MvBlogBundle'));
    
            return $this->redirect($this->generateUrl('blog_post_show', $entity->getRoutingParams()));
        }
        
        return array(
            'entity'      => $entity,
            'form'        => $form->createView(),
            'facebook_api_id'   => $this->container->getParameter('mv_blog.facebook_api_id')
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
        $t = $this->get('translator');

        $comment = $em->getRepository('MvBlogBundle:AdminBlog\Comment')->findOneBy(array('email' => $email,'token' => $token));
        
        if($comment instanceof Comment){
            if($comment->getPublied() === null){
                $comment->setPublied(new \DateTime('now'));
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', $t->trans('default.comment.confirmed', array(), 'MvBlogBundle'));
            }else
                $this->get('session')->getFlashBag()->add('error', $t->trans('default.comment.confirmed', array(), 'MvBlogBundle'));
                
            return $this->redirect($this->generateUrl('blog_post_show', $comment->getPost()->getRoutingParams()));
        }
        
        throw new HttpException(401,'Unauthorized access.');
    }
}
