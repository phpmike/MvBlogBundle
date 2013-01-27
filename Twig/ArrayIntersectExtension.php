<?php
namespace Mv\BlogBundle\Twig;

class ArrayIntersectExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'array_intersect' => new \Twig_Filter_Method($this, 'arrayIntersectFilter'),
        );
    }
    
    function arrayIntersectFilter($arr1, $arr2)
    {
        if (!is_array($arr1) || !is_array($arr2)) {
            throw new Twig_Error_Runtime('The merge filter only works with arrays or hashes.');
        }

        return array_intersect($arr1, $arr2);
    }

    public function getName()
    {
        return 'mv_extension';
    }
}