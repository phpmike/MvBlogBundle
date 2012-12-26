<?php

namespace Mv\BlogBundle\Form\AdminBlog;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
        ;
        // Peut appartenir à une Root category seulement si c'en est pas déjà une avec des enfants ou si elle est nouvelle
        // Ne peut pas être un enfant de lui-même
        if(!$builder->getData()->getId() || !$builder->getData()->getChildren()->count()){
            $builder
                    ->add('parent',null,array('query_builder' => function(NestedTreeRepository $er) use ($builder)
                                                                            {
                                                                            if(!$builder->getData()->getId())
                                                                                return $er->getRootNodesQueryBuilder();
                                                                            else
                                                                                return $er->getRootNodesQueryBuilder()
                                                                                            ->andWhere('node.id != :self_id')
                                                                                            ->setParameter('self_id',$builder->getData()->getId());
                                                                            }))
            ;
        }                                                     
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mv\BlogBundle\Entity\AdminBlog\Category'
        ));
    }

    public function getName()
    {
        return 'mv_blogbundle_blog_categorytype';
    }
}
