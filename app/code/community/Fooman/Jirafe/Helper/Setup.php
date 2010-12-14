<?php

class Fooman_Jirafe_Helper_Setup extends Mage_Core_Helper_Abstract
{

    public function getDbSchema ($version, $returnComplete=false)
    {
        $instructions = array();
        switch ($version) {
            case '0.1.1':
                $instructions = array_merge(
                        $instructions,
                            array(
                                array("type" =>"sql-column", "table" =>"foomanjirafe_report", "name" =>"store_id","params" =>"smallint(5) AFTER `report_id`"),
                                array("type" =>"sql-column", "table" =>"foomanjirafe_report", "name" =>"store_report_date","params" =>"timestamp AFTER `generated_by_jirafe_version`")
                                )
                        );
                if(!$returnComplete) {
                    break;
                }
                //nobreak intentionally;
            case '0.1.0':
                $instructions = array_merge(
                        $instructions,
                            array(
                                array("type" => "table", "name" => "foomanjirafe_report", "items" =>
                                    array(
                                        array("sql-column", "report_id", "int(10) unsigned NOT NULL auto_increment"),
                                        array("sql-column", "created_at", "timestamp NOT NULL default CURRENT_TIMESTAMP"),
                                        array("sql-column", "generated_by_jirafe_version", "varchar(128)"),
                                        array("sql-column", "report_data", "text"),
                                        array("key", "PRIMARY KEY", "report_id")
                                        )
                                    )
                                )
                        );
                if(!$returnComplete) {
                    break;
                }
                //nobreak intentionally;
            default:
                break;
        }
        return $instructions;

    }

    public function runDbSchemaUpgrade ($installer, $version)
    {
        foreach (Mage::helper('foomanjirafe/setup')->getDbSchema($version) as $instruction) {
            switch ($instruction['type']) {
                case 'table':
                    $keys = array();
                    $columns = array();

                    foreach ($instruction['items'] as $item) {
                        switch ($item[0]) {
                            case 'sql-column':
                                $columns[] = '`'.$item[1].'` '.$item[2];
                                break;
                            case 'key':
                                $keys[] = $item[1] .' (`'.$item[2].'`)';
                                break;
                        }
                    }
                    $tableDetails = implode(",",array_merge($columns,$keys));
                    $sql = "DROP TABLE IF EXISTS `{$this->getTable($instruction['name'])}`;\n";
                    $sql .="CREATE TABLE `{$this->getTable($instruction['name'])}` (".$tableDetails.") ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                    $installer->run($sql);
                    break;
                case 'sql-column':
                    $return = $installer->run("
                        ALTER TABLE `{$installer->getTable($instruction['table'])}` ADD COLUMN `{$instruction['name']}` {$instruction['params']}");
                    break;
            }

        }
    }

}
