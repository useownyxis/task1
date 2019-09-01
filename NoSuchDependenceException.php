<?php

class NoSuchDependenceException extends  Exception
{
    private $package = [];
    public function __construct(array $package, Throwable $previous = null)
    {
        $this->package = $package;
        $message = " Was found package with dependence, which are not found or the name of dependency coincides with a name of this package.";
        $code = 2;
        parent::__construct($message, $code, $previous);
    }
    public function __toString()
    {
        $srtArray = json_encode($this->package);
        return __CLASS__ . "{$this->message} Package:{$srtArray}. Exception code: {$this->code}\n";
    }

}