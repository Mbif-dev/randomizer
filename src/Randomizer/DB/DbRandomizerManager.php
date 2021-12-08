<?php

namespace Randomizer\DB;

use Exception;
use Randomizer\DataToRandomize;
use Randomizer\Report;

class DbRandomizerManager
{

    private $dbh;
    private $db;
    private $dbScanner;
    private $report;

    public function __construct(\PDO $dbh, string $db)
    {
        $this->dbh = $dbh;
        $this->report = new Report();
        if (!$this->isDbSupported($db))
            throw new \InvalidArgumentException('DB ' . $db . ' driver is not supported');
        $this->db = $db;
    }

    public function runRandomize(DataToRandomize $dataToRandomize): string
    {

        if ($dataToRandomize->hasTables()) {
            $tablesNames = $dataToRandomize->getTablesNames();
            $class = $this->getClassName('QueryBulider');
            $queryBulider = new $class();
            try {
                foreach ($tablesNames as $tableName) {
                    $table = $dataToRandomize->getTable($tableName);
                    $fields = $dataToRandomize->getTableFields($tableName);
                    $this->runSql($queryBulider->createTemporaryTable($table, $fields));
                    $this->runSql($queryBulider->addDataToTemporaryTable($table, $fields));
                    if (!empty($fields['withOutWhere'])) {
                        foreach ($fields['withOutWhere'] as $fieldName => $val)
                            $this->runSql($queryBulider->updateTable($table, $fieldName));
                    }
                    if (!empty($fields['withWhere'])) {
                        foreach ($fields['withWhere'] as $fieldName => $val)
                            $this->runSql($queryBulider->updateTable($table, $fieldName, $fields['withWhere'][$fieldName]['where']));
                    }
                    $this->runSql($queryBulider->dropTemporaryTable($table));
                }
            } catch (Exception $e) {
                $this->report->addError('Randomize data has stopped.');
            }
        } else {
            $this->report->addError('Tables to randomize data not found.');
        }

        return $this->report->getReport();
    }

    private function runSql(string $sql): bool
    {

        $ret = $this->dbh->exec($sql);
        if ($ret === false) {
            $this->report->addError('Executed with errors:' . PHP_EOL . $sql);
            $this->report->addError(var_export($this->dbh->errorInfo(),true));
            throw new Exception('SQL error.');
        }
        $this->report->addInfo('Executed:' . PHP_EOL . $sql);
        return true;
    }


    public function getAllDbTables(): array
    {
        $class = $this->getClassName('Scanner');
        $this->dbScanner = new $class($this->dbh);
        $this->dbScanner->scanDb();
        return $this->dbScanner->getDbTables();;
    }

    private function getClassName(string $className): string
    {
        return '\Randomizer\DB\\' . $this->db . '\\' . $className;
    }

    private function isDbSupported(string $db): bool
    {
        return (file_exists(__DIR__ . '/' . $db));
    }
}
