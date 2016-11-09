<?php
/**
 * Basic unit tests for Xml library.
 *
 * @package Xml\Tests
 */

namespace Xml\Tests;

/**
 * Class CommonTest
 */
class CommonTest extends \PHPUnit_Framework_TestCase
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
}
