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
            ->add('pseudo')
            ->add('email')
            ->add('web')
            ->add('ip','hidden')
            ->add('comment')
        ;
        if($this->usage === 'admin'){
            $builder->add('token')
                    ->add('ip')
                    ->add('publied')
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
