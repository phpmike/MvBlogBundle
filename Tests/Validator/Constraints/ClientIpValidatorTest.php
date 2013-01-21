<?php
namespace Mv\BlogBundle\Tests\Validator\Constraints;

use Mv\BlogBundle\Validator\Constraints\ClientIp;
use Mv\BlogBundle\Validator\Constraints\ClientIpValidator;

class ClientIpValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testIp()
    {
        $_SERVER['REMOTE_ADDR'] = '192.168.0.1';
        
        $validator  = new ClientIpValidator();
        $context = $this->getMockBuilder('Symfony\Component\Validator\ExecutionContext')->disableOriginalConstructor()->getMock();

        $context->expects($this->once())
                ->method('addViolation')
                ->with($this->equalTo('Une erreur est survenue.'));

        $validator->initialize($context);

        $validator->validate('127.0.0.1', new ClientIp());
        $validator->validate('192.168.0.1', new ClientIp());
    }
}