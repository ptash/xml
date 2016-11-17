<?php
/**
 * Unit tests for /Validate
 *
 * @package Cognitive\Xml\Tests
 */

namespace Cognitive\Xml\Tests;

use Cognitive\Xml\Validate;

/**
 * Class ValidateTest
 */
class ValidateTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test \Cognitive\Xml\Validate::__construct
     *
     * @return void
     */
    public function testValidateException()
    {
        $xsd = __DIR__ . DIRECTORY_SEPARATOR . "Data/NotExists.xsd";
        $this->setExpectedException('Cognitive\Xml\Exception\Error', "Scheme 'NotExists.xsd' not available to reading");
        new Validate($xsd);
    }

    /**
     * Test \Cognitive\Xml\Validate::__construct
     *
     * @return void
     */
    public function testValidateConstruct()
    {
        $xsd = __DIR__ . DIRECTORY_SEPARATOR . "Data/scheme.xsd";
        $validator = new Validate($xsd);
        $this->assertInstanceOf('\Cognitive\Xml\Validate', $validator);
    }

    /**
     * Test \Cognitive\Xml\Validate::validateXML
     *
     * @return void
     */
    public function testValidateXMLExceptionInt()
    {
        $xsd = __DIR__ . DIRECTORY_SEPARATOR . "Data/scheme.xsd";

        $validate = new Validate($xsd);
        $this->setExpectedException('Cognitive\Xml\Exception\Error', "Argument not string or empty value");
        $this->assertTrue($validate->validateXML(10));
    }

    /**
     * Test \Cognitive\Xml\Validate::validateXML
     *
     * @return void
     */
    public function testValidateXMLExceptionEmpty()
    {
        $xsd = __DIR__ . DIRECTORY_SEPARATOR . "Data/scheme.xsd";

        $validate = new Validate($xsd);
        $this->setExpectedException('Cognitive\Xml\Exception\Error', "Argument not string or empty value");
        $this->assertTrue($validate->validateXML(""));
    }

    /**
     * Test \Cognitive\Xml\Validate::validateXML
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
        $this->assertCount(3, $validate->getErrors());
    }

    /**
     * Test \Cognitive\Xml\Validate::validate
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
        $this->assertCount(3, $validate->getErrors());
    }

    /**
     * Test \Cognitive\Xml\Validate::getErrorsAsString
     *
     * @return void
     */
    public function testGetErrorsAsString()
    {
        $xsd = __DIR__ . DIRECTORY_SEPARATOR . "Data/scheme.xsd";
        $error = __DIR__ . DIRECTORY_SEPARATOR . "Data/scheme.error.xml";
        $validate = new Validate($xsd);
        $this->assertFalse($validate->validate($error));
        $this->assertCount(3, $validate->getErrors());

        $s = <<<'EOL'
Schema validation errors:
Error 522: Validation failed: no DTD found ! on line 2
Error 1824: Element 'createDateTime': '2008-29:45' is not a valid value of the atomic type 'xs:dateTime'. on line 4
Error 1871: Element 'position': This element is not expected. Expected is ( item ). on line 11
EOL;

        $this->assertEquals($s, $validate->getErrorsAsString('Schema validation errors', "\n"));
    }
}
