<?php

defined('C5_EXECUTE') or die("Access Denied.");

class IngredientAttributeTypeController extends Concrete5_Controller_AttributeType_Number {

    public function getDisplayValue() {
        if (is_object($this->attributeValue)) {
            $value = $this->getAttributeValue()->getValue();
        } else {
            $value = 0;
        }
        $db = Loader::db();
        $q = "select name from Ingredients where id = ?";
        $name = $db->getOne($q, array($value));
        if (strlen($name) > 0) {
            return $name;
        } else {
            return $value;
        }
    }
    public function getDisplayValueSanitized() {
        return urlencode($this->getDisplayValue());
    }

    public function form() {
        if (is_object($this->attributeValue)) {
            $value = $this->getAttributeValue()->getValue();
        }
        $db = Loader::db();
        $q = "select * from Ingredients where ebb_product = 1 order by name asc";
        $ingredients = $db->query($q);
        $ingredientSelectMenuOptions = array();
        $ingredientSelectMenuOptions[0] = "Choose Ingredient";
        while ($row = $ingredients->fetchRow()) {
            if (strlen($row["name"]) > 0) {
                $ingredientSelectMenuOptions[$row['id']] = $row['name'];
            }
        }
        $form = Loader::helper('form');
        print $form->select($this->field('value'), $ingredientSelectMenuOptions, $value);
    }

    public function validateForm($p) {
        $q = "select name from Ingredients where id = ?";
        $name = $db->query($q, array($p['value']));
        if (strlen($name) > 0) {
            return true;
        } else {
            return false;
        }
    }

}
