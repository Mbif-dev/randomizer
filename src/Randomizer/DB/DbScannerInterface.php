<?php

namespace Randomizer\DB;

interface DbScannerInterface {

    public function scanDb():void;

    public function getDbTables():array;

}
