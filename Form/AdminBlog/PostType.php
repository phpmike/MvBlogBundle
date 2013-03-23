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
            ->add('title', null, array('label' => 'Titre'))
            ->add('accroche','ckeditor', array('config_name' => 'default'))
            ->add('article','ckeditor', array('config_name' => 'extended'))
            ->add('categories', null, array('label'         => 'Catégorie(s)',
                                            'query_builder' => function(NestedTreeRepository $er){ return $er->getNodesHierarchyQueryBuilder(); },
                                            'property'      => 'selectRender'))
            ->add('publied', null, array('label' => 'Publication le'))
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
