<?php

namespace Concrete\Package\CsvAttribute;

use Loader;
use \Concrete\Core\Backup\ContentImporter as ContentImporter;
use Concrete\Package\CsvAttribute\Src\Helper\AttributeTranslation;
use \Concrete\Core\Attribute\Key\Key as AttributeKey;
use \Concrete\Core\Attribute\Key\Category as AttributeKeyCategory;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends \Concrete\Core\Package\Package {

    protected $pkgHandle = 'csv_attribute';
    protected $appVersionRequired = '5.7.0.4';
    protected $pkgVersion = '0.0.1';
    protected $pkg;

    public function getPackageDescription() {
        return t("A tutorial package showing how to easily use attributes to access complex data.");
    }

    public function getPackageName() {
        return t("Database Table Attribute Demo");
    }

    public function install() {

        // Get the package object
        $this->pkg = parent::install();

        // Installing					
        $this->installOrUpgrade();
        
    }

    public function upgrade() {
        $this->pkg = $this;
        $this->installOrUpgrade();
    }

    private function installOrUpgrade() {
        $this->installAttributeKeys($this);
        $this->importCSV($this);
    }
    
    private function installAttributeKeys($pkg) {
        $csvDemoAT = AttributeType::getByHandle("csv_demo");
        if (!$csvDemoAT) {
            $csvDemoAT = AttributeType::add("csv_demo", "CSV Demo", $pkg);
            $cakc = AttributeKeyCategory::getByHandle('collection');
            $cakc->associateAttributeKeyType($csvDemoAT);
        }
    }
    
    private function importCSV($pkg){
        
    }
    
}
