<?php

namespace Concrete\Package\TutorialDatabaseTableAttribute;

use Loader;
use \Concrete\Core\Backup\ContentImporter as ContentImporter;
use Concrete\Package\CsvAttribute\Src\Helper\AttributeTranslation;
use \Concrete\Core\Attribute\Key\Key as AttributeKey;
use \Concrete\Core\Attribute\Key\Category as AttributeKeyCategory;
use \Concrete\Core\Attribute\Type as AttributeType;
use \Concrete\Core\Http\Service\Json;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends \Concrete\Core\Package\Package {

    protected $pkgHandle = 'tutorial_database_table_attribute';
    protected $appVersionRequired = '5.7.0.4';
    protected $pkgVersion = '0.0.10';
    protected $pkg;

    public function getPackageDescription() {
        return t("A tutorial package showing how to easily use attributes to access complex data.");
    }

    public function getPackageName() {
        return t("Database Table Attribute");
    }

    public function install() {

        // Get the package object
        $this->pkg = parent::install();

        // Installing					
        $this->installOrUpgrade();
        
    }

    public function upgrade() {
        $this->pkg = parent::upgrade();
        $this->clearSampleData();
        $this->installOrUpgrade();
    }

    private function installOrUpgrade() {
        $this->installAttributeKeys($this->pkg);
        $this->installSampleData($this);
    }

    public function uninstall()
    {
        parent::uninstall();
        $db = \Database::get();
        $db->query("drop table TutorialDbTableDemoAttribute");
    }
    
    private function installAttributeKeys($pkg) {
        $dbTableDemoAT = AttributeType::getByHandle("db_table_demo");
        if (!$dbTableDemoAT) {
            $dbTableDemoAT = AttributeType::add("db_table_demo", "Database Table Demo", $pkg);
            $cakc = AttributeKeyCategory::getByHandle('collection');
            $cakc->associateAttributeKeyType($dbTableDemoAT);
            $uakc = AttributeKeyCategory::getByHandle('user');
            $uakc->associateAttributeKeyType($dbTableDemoAT);
            $fakc = AttributeKeyCategory::getByHandle('file');
            $fakc->associateAttributeKeyType($dbTableDemoAT);
        }
    }
    
    private function installSampleData($pkg){
        $db = \Database::get();
        $json = new Json();
        $q = "insert into TutorialDbTableDemoAttribute (keyID, displayTitle, data) values (?,?,?)";
        for ($i = 1; $i <= 10; $i++){
            $item = array();
            // keyID
            $item[] = $i;
            // displayTitle
            $item[] = t("Display Title (Key = " . $i . ")");
            // data
            $itemData = array();
            $itemData["Department Name"] = "Facility Name " . $i;
            $itemData["Address 1"] = "Address 1 (Key = " . $i . ")";
            $itemData["Address 2"] = "Address 2 (Key = " . $i . ")";
            $itemData["City"] = "City (Key = " . $i . ")";
            $itemData["State"] = "State (Key = " . $i . ")";
            $itemData["Zip"] = "Zip (Key = " . $i . ")";
            $item[] = $json->encode($itemData);
            $db->query($q, $item);
        }
    }

    private function clearSampleData(){
        $db = \Database::get();
        $db->query("truncate table TutorialDbTableDemoAttribute");
    }
    
}
