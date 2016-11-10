<?php
/**
 * Unit tests for /Validate/Error
 *
 * @package Cognitive\Xml\Tests\Validate
 */

namespace Cognitive\Xml\Tests\Validate;

use Cognitive\Xml\Validate;

/**
 * Class ErrorTest
 */
class ErrorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Testing Validate/Error
     *
     * @return void
     */
    public function testError()
    {
        $error = new Validate\Error(LIBXML_ERR_WARNING, 100, 'Error messge', 23);

        $this->assertEquals(LIBXML_ERR_WARNING, $error->getLevel());
        $this->assertEquals(100, $error->getCode());
        $this->assertEquals('Error messge', $error->getMessage());
        $this->assertEquals(23, $error->getLine());

        $this->assertEquals('Warning 100: Error messge on line 23', (string)$error);
    }
}
