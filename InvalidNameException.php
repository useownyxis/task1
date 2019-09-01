<?php

class InvalidNameException extends Exception
{
    private $package = [];
    private $field = '';
    public function __construct(array $package,string $field, Throwable $previous = null)
    {
        $message = " Was found package with empty ";
        $code = 1;
        $this->package = $package;
        $this->field = $field;
        parent::__construct($message, $code, $previous);
    }
    public function __toString()
    {
        $srtArray = json_encode($this->package);
        return __CLASS__ . "{$this->message}'{$this->field}'. Package: {$srtArray}. Exception code: {$this->code}\n";
    }
}