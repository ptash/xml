<?php
/**
 * Ошибка проверки схемы.
 *
 * @package Xml\Validate
 */

namespace Xml\Validate;

/**
 * Class Error
 */
class Error
{

    /**
     * Уровень ошибки.
     *
     * @var integer
     */
    private $level;

    /**
     * Код ошибки.
     *
     * @var integer
     */
    private $code;

    /**
     * Сообщение об ошибке.
     *
     * @var string
     */
    private $message;

    /**
     * Место возникновения ошибки.
     *
     * @var string
     */
    private $line;

    /**
     * Error constructor.
     *
     * @param int    $level   Уровень ошибки.
     * @param int    $code    Код ошибки.
     * @param string $message Сообщение об ошибке.
     * @param string $line    Место возникновения ошибки.
     */
    public function __construct($level, $code, $message, $line)
    {
        $this->level = $level;
        $this->code = $code;
        $this->message = trim($message);
        $this->line = $line;
    }

    /**
     * Ошибка строкой.
     *
     * @return string
     */
    public function __toString()
    {
        $map = array(
            LIBXML_ERR_WARNING => "Warning",
            LIBXML_ERR_ERROR => "Error",
            LIBXML_ERR_FATAL => "Fatal Error",
        );
        $getErr = function ($errorId) use ($map) {
            return isset($map[$errorId]) ? $map[$errorId] : "Undefined Error";
        };
        return $getErr($this->level) . " {$this->code}: {$this->message} on line {$this->line}";
    }

    /**
     * Уровень ошибки.
     *
     * @return int Уровень ошибки.
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Код ошибки.
     *
     * @return int Код ошибки.
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Сообщение об ошибке.
     *
     * @return string Сообщение об ошибке.
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Место возникновения ошибки.
     *
     * @return string Место возникновения ошибки.
     */
    public function getLine()
    {
        return $this->line;
    }
}
