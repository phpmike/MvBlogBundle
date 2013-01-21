<?php
namespace Mv\BlogBundle\Tests\Validator\Constraints;

use Mv\BlogBundle\Validator\Constraints\NoTmpMail;
use Mv\BlogBundle\Validator\Constraints\NoTmpMailValidator;

class KernelLike {
    public function getContainer(){
        return $this;
    }
    public function getParameter($name){
        return array('jetable.org','yopmail.com','mail-temporaire.fr','get2mail.fr','courrieltemporaire.com','mailcatch.com');
    }
}

class NoTmpMailValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testNoTmpMail()
    {
        global $kernel;
        
        if(isset($kernel))
            $old_kernel = $kernel;
        
        $kernel = new KernelLike();
        
        $validator  = new NoTmpMailValidator();
        $context = $this->getMockBuilder('Symfony\Component\Validator\ExecutionContext')->disableOriginalConstructor()->getMock();

        $context->expects($this->exactly(3))
                ->method('addViolation')
                ->with($this->equalTo('L\'adresse %string% est rejetée.'),$this->arrayHasKey('%string%'));

        $validator->initialize($context);

        $validator->validate('test@jetable.org', new NoTmpMail());
        $validator->validate('teGGxbgf54st@yopmail.com', new NoTmpMail());
        $validator->validate('tr-jui.54556fr@mail-temporaire.fr', new NoTmpMail());
        $validator->validate('adresse.normale@gmail.com', new NoTmpMail());
        
        if(isset($old_kernel))
            $kernel = $old_kernel;
    }
}