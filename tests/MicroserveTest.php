<?php

use PHPUnit\Framework\TestCase;
use Desilva\Microserve\Microserve;
use Desilva\Microserve\Application;
use Desilva\Microserve\HttpKernel;

/**
 * @covers \Desilva\Microserve\Microserve
 */
class MicroserveTest extends TestCase
{
    public function testBoot()
    {
        $kernelClass = get_class($this->getMockForAbstractClass(HttpKernel::class));
        
        $app = Microserve::boot($kernelClass);
        
        $this->assertInstanceOf(Application::class, $app);
    }
}
