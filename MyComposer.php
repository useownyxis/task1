<?php
declare(strict_types=1);

require 'NoArgumentException.php';
require 'InvalidNameException.php';
require 'CycleException.php';
require 'NoSuchDependenceException.php';

class MyComposer
{
    private $packages = [];
    private $isInstalled = [];

    private $predDependence = [];
    private $cycles = [];
    private $cycleStart = '';
    private $cycleEnd = '';


    private function getSize(array $array):int{
        return count($array);
    }

    private function validateArguments(array $array){
        foreach ($array as $element){
            try{
                $this->validateArgument($element);
            }catch (NoArgumentException $exception){
                echo $exception;
                exit(0);
            }
        }
    }

    private function validateArgument(array $array){
        if (!array_key_exists('name', $array )){
            throw new NoArgumentException('name',$array);
        }
        if (!array_key_exists('dependencies',$array)){
            throw new NoArgumentException('dependencies',$array);
        }
    }

   private function validateName(array $array){
        if ($array['name'] == '') {
            throw new InvalidNameException($array,'name');
        }
        foreach ($array['dependencies'] as $element){
            if ($element == ''){
                throw new InvalidNameException($array,'dependencies');
            }
        }
    }

    private function validateNames(array  $array){
        foreach ($array as $element){
            try{
                $this->validateName($element);
            }catch (InvalidNameException $exception){
                echo $exception;
                exit(0);
            }
        }
    }

    private function validateDependencies(array  $array){
        foreach ($array as $element){
            try{
                $this->validateDependency($array,$element);
            }catch (NoSuchDependenceException $exception){
                echo $exception;
                exit(0);
            }
        }
    }

    private function validateDependency(array $array,array $element){
        foreach ($element['dependencies'] as $value){
            if (array_search($value,array_column($array, 'name')) ===false ||  $value == $element['name']){
                throw new NoSuchDependenceException($element);
            }
        }
    }

    private function validateCycles(array $array){
        $this->preparationFinding($array);
        foreach ($array as $element){
            try{
                $this->findCycles($array,$element);
            }catch (CycleException $exception){
                echo $exception;
                exit(0);
            }
        }
    }

    private  function findCycles(array $array, array $element):bool{
        $name = $element['name'];
        $this->cycles[$name] = 1;
        foreach ($element['dependencies'] as $nextDependency){
            if ($this->cycles[$nextDependency] == 0) {
                $this->predDependence[$nextDependency] = $name;
                if ($this->findCycles($array, $array[array_search($nextDependency, array_column($array, 'name'))])) {
                    throw new CycleException($this->cycles,$this->predDependence, $this->cycleStart,$this->cycleEnd);
                }
            }elseif($this->cycles[$nextDependency] == 1){
                $this->cycleEnd = $element['name'];
                $this->cycleStart = $nextDependency;
                return true;
            }
        }
        $this->cycles[$name] = 2;
        return false;
    }

    private function preparationFinding(array $array){
        foreach ($array as $element){
            $this->predDependence[$element['name']] = -1;
            $this->cycles[$element['name']] = 0;
        }
    }

    public function install(array  $array){
        $this->validateArguments($array);
        $this->validateNames($array);
        $this->validateDependencies($array);
        $this->validateCycles($array);
        $this->packages = $array;
        $this->installPackages();
    }

    private function installPackages(){
        $this->preparationInstall();
        echo "INSTALLING:\n";
        foreach ($this->packages as $package){
            if (!$this->isInstalled[$package['name']]){
                $this->installPackage($package);
            }
        }
    }

    private function installPackage($package){
        $this->isInstalled[$package['name']] = true;
        foreach ($package['dependencies'] as $dependensy){
            $predPackage = $this->packages[array_search($dependensy, array_column($this->packages, 'name'))];
            if (!$this->isInstalled[$predPackage['name']]){
                $this->installPackage($predPackage);
            }
        }
        echo "NEXT: {$package['name']}\n";
    }

    private function preparationInstall(){
        foreach ($this->packages as $element){
            $this->isInstalled[$element['name']] = false;
        }
    }

}