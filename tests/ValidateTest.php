<?php
/**
 * Unit tests for /Validate
 *
 * @package Xml\Tests
 */

namespace Xml\Tests;

use Xml\Validate;

/**
 * Class ValidateTest
 */
class ValidateTest extends \PHPUnit_Framework_TestCase
{

    /**
     * System test.
     *
     * @return void
     */
    public function testRequiredLibraries()
    {

        $this->assertTrue(function_exists('\libxml_use_internal_errors'));
        $this->assertTrue(function_exists('\libxml_get_errors'));
        $this->assertTrue(function_exists('\libxml_clear_errors'));
        $this->assertTrue(class_exists('\DOMDocument'));
    }


    /**
     * Test \Xml\Validate
     *
     * @return void
     */
    public function testValidateXsd()
    {
        $xsd = __DIR__ . DIRECTORY_SEPARATOR . "Data/NotExists.xsd";
        $this->setExpectedException('Xml\Exception\Error');
        new Validate($xsd);

        $xsd = __DIR__ . DIRECTORY_SEPARATOR . "Data/scheme.xsd";
        new Validate($xsd);
    }

    /**
     * Test \Xml\Validate::validateXML
     *
     * @return void
     */
    public function testValidateXML()
    {
        $xsd = __DIR__ . DIRECTORY_SEPARATOR . "Data/scheme.xsd";
        $valid = __DIR__ . DIRECTORY_SEPARATOR . "Data/scheme.xml";
        $error = __DIR__ . DIRECTORY_SEPARATOR . "Data/scheme.error.xml";

        $validate = new Validate($xsd);
        $this->assertTrue($validate->validateXML(file_get_contents($valid)));

        $validate = new Validate($xsd);
        $this->assertFalse($validate->validateXML(file_get_contents($error)));
        $this->assertCount(4, $validate->getErrors());
    }

    /**
     * Test \Xml\Validate::validate
     *
     * @return void
     */
    public function testValidate()
    {
        $xsd = __DIR__ . DIRECTORY_SEPARATOR . "Data/scheme.xsd";
        $valid = __DIR__ . DIRECTORY_SEPARATOR . "Data/scheme.xml";
        $error = __DIR__ . DIRECTORY_SEPARATOR . "Data/scheme.error.xml";

        $validate = new Validate($xsd);
        $this->assertTrue($validate->validate($valid));

        $validate = new Validate($xsd);
        $this->assertFalse($validate->validate($error));
        $this->assertCount(4, $validate->getErrors());
    }
}
