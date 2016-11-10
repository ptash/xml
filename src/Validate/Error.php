<?php
/**
 * Error found in validation process.
 *
 * @package Cognitive\Xml\Validate
 */

namespace Cognitive\Xml\Validate;

/**
 * Class Error
 */
class Error
{

    /**
     * Error level.
     *
     * @var integer
     */
    private $level;

    /**
     * Error code.
     *
     * @var integer
     */
    private $code;

    /**
     * Error message.
     *
     * @var string
     */
    private $message;

    /**
     * Error line.
     *
     * @var string
     */
    private $line;

    /**
     * Error constructor.
     *
     * @param int    $level   Error level.
     * @param int    $code    Error code.
     * @param string $message Error message.
     * @param string $line    Error line.
     */
    public function __construct($level, $code, $message, $line)
    {
        $this->level = $level;
        $this->code = $code;
        $this->message = trim($message);
        $this->line = $line;
    }

    /**
     * Convert error to string.
     *
     * @return string
     */
    public function __toString()
    {
        $map = array(
            LIBXML_ERR_WARNING => "Warning",
            LIBXML_ERR_ERROR => "Error",
            LIBXML_ERR_FATAL => "Fatal Error",
            LIBXML_ERR_NONE => 'None'
        );
        $getErr = function ($errorId) use ($map) {
            return isset($map[$errorId]) ? $map[$errorId] : "Undefined Error";
        };
        return $getErr($this->level) . " {$this->code}: {$this->message} on line {$this->line}";
    }

    /**
     * Error level.
     *
     * @return int Error level.
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Error code.
     *
     * @return int Error code.
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Error message.
     *
     * @return string Error message.
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Error line.
     *
     * @return string Error line.
     */
    public function getLine()
    {
        return $this->line;
    }
}
