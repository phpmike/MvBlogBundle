<?php

namespace Mv\BlogBundle\Form\AdminBlog;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\RegexValidator;

class CommentType extends AbstractType
{
    protected $usage;
    
    public function __construct($usage = 'public') {
        $this->usage = $usage;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', null, array('label' => 'admin.comment.pseudo', 'translation_domain' => 'MvBlogBundle'))
            ->add('email')
            ->add('web', null, array('label' => 'form.comment.website', 'translation_domain' => 'MvBlogBundle'))
            ->add('ip','hidden')
            ->add('comment', null, array('label' => 'form.comment.comment', 'translation_domain' => 'MvBlogBundle'))
        ;
        if($this->usage === 'admin'){
            $builder->add('token')
                    ->add('ip')
                    ->add('publied', null, array('label' => 'form.comment.published', 'translation_domain' => 'MvBlogBundle'))
            ;
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mv\BlogBundle\Entity\AdminBlog\Comment'
        ));
    }

    public function getName()
    {
        return 'mv_blogbundle_adminblog_commenttype';
    }
}
