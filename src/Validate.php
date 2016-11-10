<?php
/**
 * Проверка XML на соответствие схеме.
 *
 * @package XML
 */

namespace XML;

use XML\Exception\Error as ExceptionError;
use XML\Exception\OutOfMemory;
use XML\Validate\Error;

/**
 * Class Validate
 */
class Validate
{
    /**
     * Пусть до схемы.
     *
     * @var mixed
     */
    protected $xsd;

    /**
     * Представляет все содержимое XML документа.
     *
     * @var \DOMDocument
     */
    protected $xml;

    /**
     * Массив ошибок полученых в результате проверки.
     *
     * @var Error[]
     */
    private $errors = array();


    /**
     * Validate constructor.
     *
     * @param string $xsd Расположение XSD схемы.
     *
     * @throws ExceptionError Ошибка чтения схемы.
     */
    public function __construct($xsd)
    {
        if (! is_file($xsd)) {
            $name = basename($xsd);
            throw new ExceptionError("Схема '$name' не доступна для чтения");
        }
        $this->xsd = $xsd;
        libxml_use_internal_errors(true);
        $this->xml = new \DOMDocument();
        $this->xml->validateOnParse = true;
    }

    /**
     * Проверка переданной строки XML на соответствие схеме.
     *
     * @param string $content Строка для проверки.
     *
     * @return bool Результат проверки.
     */
    public function validateXML($content)
    {
        $this->xml->loadXML($content);
        return $this->schemaValidate();
    }

    /**
     * Проверка переданного файла XML на соответствие схеме.
     *
     * @param string $path Расположение файла.
     *
     * @return bool Результат проверки.
     *
     * @throws ExceptionError Ошибка чтения файла для проверки.
     */
    public function validate($path)
    {
        if (! is_file($path)) {
            throw new ExceptionError("Файл для проверки по схеме не доступен для чтения");
        }
        $this->xml->load($path);
        return $this->schemaValidate();
    }

    /**
     * Проверка на соотвествие схеме.
     *
     * @return bool Результат проверки.
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
     * Определение ошибок в ходе проверки XML на соответствие схеме.
     *
     * @return void
     * @throws OutOfMemory Исключение нехватки памяти.
     */
    private function collectErrors()
    {
        foreach (libxml_get_errors() as $error) {
            if (strpos($error->code, 'out of memory')) {
                throw new OutOfMemory("Закончилась память при проверке по схеме");
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
     * Ошибки строкой.
     *
     * @return string Ошибки.
     */
    public function getErrorsAsString()
    {
        $text = "Ошибки проверки по схеме: \r\n";
        foreach ($this->errors as $err) {
            $text .= "$err\r\n";
        }
        return $text;
    }

    /**
     * Массив ошибок.
     *
     * @return Error[]
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
