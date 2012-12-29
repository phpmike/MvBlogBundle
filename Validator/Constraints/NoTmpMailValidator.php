<?php
/**
 * Description of NoTmpMailValidator
 *
 * @author michael
 */
namespace Mv\BlogBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NoTmpMailValidator extends ConstraintValidator {
    
    protected function _getRegex(){
        global $kernel;
        $hosts = array_map(function($str){ return preg_replace('#(?<!\\\)\.#', '\.', $str); },$kernel->getContainer()->getParameter('mv_blog.hosts_tmp_mail'));
        return implode('|', $hosts);
    }

    public function validate($value, Constraint $constraint)
    {
        if (!preg_match('#.+@(?!'. $this->_getRegex() .')#i', $value)) {
            $this->context->addViolation($constraint->message, array('%string%' => $value));
        }
    }  
}

?>
