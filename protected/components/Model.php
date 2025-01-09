<?php
/**
 * =======================================
 * ###################################
 * MagnusBilling
 *
 * @package MagnusBilling
 * @author Adilson Leffa Magnus.
 * @copyright Copyright (C) 2005 - 2021 MagnusSolution. All rights reserved.
 * ###################################
 *
 * This software is released under the terms of the GNU Lesser General Public License v2.1
 * A copy of which is available from http://www.gnu.org/copyleft/lesser.html
 *
 * Please submit bug reports, patches, etc to https://github.com/magnusbilling/mbilling/issues
 * =======================================
 * Magnusbilling.com <info@magnusbilling.com>
 *
 */

class Model extends CActiveRecord
{

    public function getModule()
    {
        return $this->_module;
    }

    public function getExtraField($rules)
    {
        if (isset($_SESSION['module_extra'][$this->getModule()])) {

            foreach ($_SESSION['module_extra'][$this->getModule()] as $key => $value) {
                $rules[] = [$value, 'length', 'max' => 500];
            }
        }
        return $rules;
    }

    public function uniquePeerName($attribute, $params)
    {

        if (isset($this->name)) {
            $modelTrunk = Trunk::model()->find('trunkcode = :key', array(':key' => $this->name));
            if (isset($modelTrunk->id)) {
                $this->addError($attribute, Yii::t('zii', 'This username is in use by a trunk'));
            }
        } else if (isset($this->trunkcode)) {

            $modelSip = Sip::model()->find('name = :key', array(':key' => $this->trunkcode));
            if (isset($modelSip->id)) {
                $this->addError($attribute, Yii::t('zii', 'This trunk name is in use by a SIP user'));
            }
        }

    }

    public function generateRules($rules = [])
    {
        $table = array($this->getTableSchema($this->tableName()));

        $required  = array();
        $integers  = array();
        $numerical = array();
        $length    = array();
        $safe      = array();

        foreach ($table[0]->columns as $column) {

            if ($column->autoIncrement) {
                continue;
            }

            $r = !$column->allowNull && $column->defaultValue === null;
            if ($r) {
                $required[] = $column->name;
            }

            if ($column->type === 'integer') {
                $integers[] = $column->name;
            } elseif ($column->type === 'double') {
                $numerical[] = $column->name;
            } elseif ($column->type === 'string' && $column->size > 0) {
                $length[$column->size][] = $column->name;
            } elseif (!$column->isPrimaryKey && !$r) {
                $safe[] = $column->name;
            }

        }
        if ($required !== array()) {

            $rules[] = [implode(', ', $required), 'required'];
        }

        if ($integers !== array()) {
            $rules[] = [implode(', ', $integers), 'numerical', 'integerOnly' => true];
        }

        if ($numerical !== array()) {
            $rules[] = [implode(', ', $numerical), 'numerical'];
        }

        if ($length !== array()) {
            foreach ($length as $len => $cols) {
                $rules[] = [implode(', ', $cols), 'length', 'max' => $len];
            }

        }
        if ($safe !== array()) {
            $rules[] = [implode(', ', $safe), 'safe'];
        }

        return $this->getExtraField($rules);
    }
}
