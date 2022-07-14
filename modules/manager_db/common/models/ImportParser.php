<?php

namespace modules\manager_db\common\models;

use Yii;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ImportParser
{

    public $tables;
    public $tableID;
    public $tableName;
    public $json;
    public $path = 'modules/manager_db/common/models/parsers/';

    public function __construct($tables, $model)
    {
        $this->tables    = $tables;
        $this->tableID   = $model->table_id;
        $this->json      = $model->json;
        $this->tableName = $this->findTableName();
    }

    public function run()
    {
        if ($this->tableName && $this->tableExist()) {
            if ($parser = $this->parserClass()) {
                $model = new $parser($this->tableName);
                return $model->import($this->json);
            }
        }
    }

    public function findTableName()
    {
        return (isset($this->tables[$this->tableID])) ? $this->tables[$this->tableID] : false;
    }

    public function tableExist()
    {
        return (Yii::$app->db->schema->getTableSchema($this->tableName)) ? true : false;
    }

    public function parserClass()
    {
        $part = explode('_', $this->tableName);

        $part = array_map(function($value) {
            return ucfirst($value);
        }, $part);
        $className = implode($part);

        if (file_exists(Yii::getAlias('@base') . '/' . $this->path . $className . '.php')) {
            return "modules\manager_db\common\models\parsers\\" . $className;
        }
    }

}
