<?php

use Desilva\Microserve\Application;
use Desilva\Microserve\HttpKernel;
use Desilva\Microserve\Microserve;
use PHPUnit\Framework\TestCase;

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
