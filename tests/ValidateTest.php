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
     * Test \Cognitive\Xml\Validate
     *
     * @return void
     */
    public function testValidateXsd()
    {
        $xsd = __DIR__ . DIRECTORY_SEPARATOR . "Data/NotExists.xsd";
        $this->setExpectedException('Cognitive\Xml\Exception\Error');
        new Validate($xsd);

        $xsd = __DIR__ . DIRECTORY_SEPARATOR . "Data/scheme.xsd";
        new Validate($xsd);
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
