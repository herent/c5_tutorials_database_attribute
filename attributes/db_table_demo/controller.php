<?php

namespace Concrete\Package\TutorialDatabaseTableAttribute\Attribute\DbTableDemo;

use Loader;
use \Concrete\Core\Foundation\Object;
use \Concrete\Core\Attribute\Controller as AttributeTypeController;
use \Concrete\Attribute\Number\Controller as NumberAttributeTypeController;
use \Concrete\Core\Http\Service\Json;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends NumberAttributeTypeController {
    
    protected $srcDataTable = 'btContentLocal';
    
    public function searchForm($list) {
        $searchValue = $this->request('value');
        $list->filterByAttribute($this->attributeKey->getAttributeKeyHandle(), $searchValue, '=');
        return $list;
    }

    public function search() {
        $this->form();
    }

    public function form() {

        if (is_object($this->attributeValue)) {
            $value = $this->getAttributeValue()->getValue();
        }
        $db = \Database::get();
        $q = "select keyID, displayTitle from TutorialDbTableDemoAttribute order by displayTitle asc";
        $vals = $db->query($q);
        $selectMenuOptions = array();
        $selectMenuOptions[0] = "-- Choose --";
        while ($row = $vals->fetchRow()) {
            if (strlen($row["displayTitle"]) > 0) {
                $selectMenuOptions[$row['keyID']] = $row['displayTitle'];
            }
        }
        $form = Loader::helper('form');
        print $form->select($this->field('value'), $selectMenuOptions, $value);
        
    }

    public function validateForm($p) {
        $db = \Database::get();
        $q = "select keyID from TutorialDbTableDemoAttribute where keyID = ?";
        $keyID = $db->getOne($q, array($p['value']));
        return intval($keyID) > 0 != false;
    }

    public function validateValue() {
        $val = $this->getValue();
        $p = array("value" => $val);
        return $this->validateForm($p);
    }

    public function getDisplayValue() {
        $db = \Database::get();
        $q = "select * from TutorialDbTableDemoAttribute where keyID = ?";
        $row = $db->getRow($q, array($this->getValue()));
        $json = new Json();
        $jsonData = $json->decode($row['data']);
        ob_start();?>
<h4><?= $row['displayTitle'];?></h4>
<dl>
    <?php foreach($jsonData as $handle => $value){?>
    <dt><?= $handle;?></dt>
    <dd><?= $value;?></dd>
    <?php } ?>
</dl>
        <?php return ob_get_clean();

    }

    public function getDisplayValueSanitized() {
        return $this->getDisplayValue();
    }
}
