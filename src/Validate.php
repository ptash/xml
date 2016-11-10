<?php
/**
 * XML validation against a schema.
 *
 * @package XML
 */

namespace Cognitive\Xml;

use Cognitive\Xml\Exception\Error as ExceptionError;
use Cognitive\Xml\Exception\OutOfMemory;
use Cognitive\Xml\Validate\Error;

/**
 * Class Validate
 */
class Validate
{
    /**
     * Scheme path.
     *
     * @var mixed
     */
    protected $xsd;

    /**
     * XML DOM Document.
     *
     * @var \DOMDocument
     */
    protected $xml;

    /**
     * Array of errors resulting from scheme validation.
     *
     * @var Error[]
     */
    private $errors = array();


    /**
     * Validate constructor.
     *
     * @param string $xsd Path to XSD scheme.
     *
     * @throws ExceptionError Error reading scheme file.
     */
    public function __construct($xsd)
    {
        if (! is_file($xsd) || !is_readable($xsd)) {
            $name = basename($xsd);
            throw new ExceptionError("Scheme '$name' not available to reading");
        }
        $this->xsd = $xsd;
        libxml_use_internal_errors(true);
        libxml_clear_errors();
        $this->xml = new \DOMDocument();
        $this->xml->validateOnParse = true;
    }

    /**
     * Checking XML string against a schema.
     *
     * @param string $content String to checking.
     *
     * @return bool Result of validation.
     *
     * @throws ExceptionError Checking content is not valid.
     */
    public function validateXML($content)
    {
        if (!is_string($content) || empty($content)) {
            throw new ExceptionError("Argument not string or empty value.");
        }
        $this->xml->loadXML($content);
        return $this->schemaValidate();
    }

    /**
     * Checking XML file against the schema.
     *
     * @param string $path Path to XML file.
     *
     * @return bool Result of validation.
     *
     * @throws ExceptionError Error reading file to checking.
     */
    public function validate($path)
    {
        if (! is_file($path) || !is_readable($path)) {
            throw new ExceptionError("XML file not available to reading");
        }
        $this->xml->load($path);
        return $this->schemaValidate();
    }

    /**
     * Checking for compliance with the scheme.
     *
     * @return bool Result of checking.
     */
    private function schemaValidate()
    {
        if (! $this->xml->schemaValidate($this->xsd)) {
            $this->collectErrors();
            return false;
        }
        return true;
    }

    /**
     * Determination errors in the XML validation against a schema.
     *
     * @return void
     * @throws OutOfMemory Out of memory exception.
     */
    private function collectErrors()
    {
        foreach (libxml_get_errors() as $error) {
            if (strpos($error->code, 'out of memory')) {
                throw new OutOfMemory("Out of memory when testing scheme");
            }
            $this->errors[] = new Error(
                $error->level,
                $error->code,
                $error->message,
                $error->line
            );
        }
        libxml_clear_errors();
    }

    /**
     * Retrieve errors as string
     *
     * @param string $pre Prefix text to error string.
     * @param string $nl  New line separator default is PHP_EOL.
     *
     * @return string Errors.
     */
    public function getErrorsAsString($pre = "Schema validation errors", $nl = PHP_EOL)
    {
        $a = array();
        if (!empty($pre)) {
            $a[] = "$pre:";
        }
        foreach ($this->errors as $err) {
            $a[] = (string)$err;
        }
        return join($nl, $a);
    }

    /**
     * Array of errors.
     *
     * @return Error[]
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
