<?php
namespace Mv\BlogBundle\Twig;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Description of ParameterExtension
 *
 * @author Michaël VEROUX <mveroux@orupaca.fr>
 */
class ParameterExtension extends \Twig_Extension
{
    protected $_parameterBag = null;


    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->_parameterBag = $parameterBag;
    }

    public function getParameterBag()
    {
        return $this->_parameterBag;
    }

    public function getFilters()
    {
        return array(
            'parameter' => new \Twig_Filter_Method($this, 'getParameter'),
        );
    }

    function getParameter($string)
    {
        try{
            return $this->getParameterBag()->get($string);
        }
        catch(\Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException $e)
        {
            throw new Twig_Error_Runtime('Unknown param ' . $string);
        }
    }

    public function getName()
    {
        return 'mv_extension_param';
    }
}

?>
