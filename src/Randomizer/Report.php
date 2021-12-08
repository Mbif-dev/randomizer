<?php

namespace Randomizer;


class Report {

    private $errors=[];
    private $infos=[];

    public function addError(string $error):void{
        $this->errors[]=$error;
    }

    public function addInfo(string $info):void{
        $this->infos[]=$info;
    }

    public function getReport():string{
        $report='';
        $report=implode(PHP_EOL,$this->errors).PHP_EOL.implode(PHP_EOL,$this->infos);
        return $report;
    }
}