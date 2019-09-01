<?php

class CycleException extends Exception
{
    private $cycles = [];
    private $predPackage = [];
    private $cycleStart ='';
    private $cycleEnd = '';
    public function __construct(array $cycles,array $predPackage, string $cycleStart,string $cycleEnd,Throwable $previous = null)
    {
        $this->cycles = $cycles;
        $this->cycleEnd = $cycleEnd;
        $this->cycleStart = $cycleStart;
        $this->predPackage = $predPackage;
        $message = "Was found cycle dependence";
        $code = 3;
        parent::__construct($message, $code, $previous);
    }
    public function __toString()
    {
        $stringCycle = $this->constructCycle($this->cycles, $this->cycleStart, $this->cycleEnd);
        return __CLASS__ . "{$this->message}\nCycle:$stringCycle\nCode:{$this->code}\n";
    }
    private function constructCycle(array $cycle, string $cycleStart, string $cycleEnd){
        $array = [];
        array_push($array, $cycleStart);
        for ($nextElement = $cycleEnd; $nextElement != $cycleStart; $nextElement = $this->predPackage[$nextElement]){
            array_push($array, $nextElement);
        }
        array_push($array, $cycleStart);
        $array = array_reverse($array);
        $returnCycle = '';
        for ($i = 0; $i < count($array)-1; $i++){
            $returnCycle .= $array[$i]."->";
        }
        $returnCycle .= $array[count($array)-1];
        return $returnCycle;
    }
}