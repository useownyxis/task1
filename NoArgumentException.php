<?php

class NoArgumentException extends Exception
{
    private $package = [];
    private $field;
    public function __construct(string $field,array $package,Throwable $previous = null)
    {
        $this->package = $package;
        $this->field = $field;
        $message = " Was found package, which does not have field:";
        $code = 0;
        parent::__construct($message, $code, $previous);
    }
    public function __toString()
    {
        $srtArray = json_encode($this->package);
        return __CLASS__ . "{$this->message} '{$this->field}'. Package:{$srtArray}. Exception code: {$this->code}\n";
    }
}