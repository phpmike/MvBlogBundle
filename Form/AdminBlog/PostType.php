<?php

namespace Mv\BlogBundle\Form\AdminBlog;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array('label' => 'form.post.title', 'translation_domain' => 'MvBlogBundle'))
            ->add('accroche','ckeditor', array('label' => 'form.post.hang', 'translation_domain' => 'MvBlogBundle'))
            ->add('article','ckeditor')
            ->add('categories', null, array('label' => 'form.post.categories',
                                            'translation_domain' => 'MvBlogBundle',
                                            'query_builder' => function(NestedTreeRepository $er){ return $er->getNodesHierarchyQueryBuilder(); },
                                            'property'      => 'selectRender'))
            ->add('publied', null, array('label' => 'form.post.publication', 'translation_domain' => 'MvBlogBundle'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mv\BlogBundle\Entity\AdminBlog\Post'
        ));
    }

    public function getName()
    {
        return 'mv_blogbundle_blog_posttype';
    }
}
