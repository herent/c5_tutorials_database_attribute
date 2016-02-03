<?php

namespace Concrete\Package\TutorialDatabaseTableAttribute;

use Loader;
use \Concrete\Core\Backup\ContentImporter as ContentImporter;
use Concrete\Package\CsvAttribute\Src\Helper\AttributeTranslation;
use \Concrete\Core\Attribute\Key\Key as AttributeKey;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;
use \Concrete\Core\Attribute\Key\UserKey as UserAttributeKey;
use \Concrete\Core\Attribute\Key\FileKey as FileAttributeKey;
use \Concrete\Core\Attribute\Key\Category as AttributeKeyCategory;
use \Concrete\Core\Attribute\Type as AttributeType;
use \Concrete\Core\Http\Service\Json;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends \Concrete\Core\Package\Package
{
    protected $pkgHandle          = 'tutorial_database_table_attribute';
    protected $appVersionRequired = '5.7.0.4';
    protected $pkgVersion         = '0.0.13';
    protected $pkg;

    public function getPackageDescription()
    {
        return t("A tutorial package showing how to easily use attributes to access complex data.");
    }

    public function getPackageName()
    {
        return t("Database Table Attribute");
    }

    public function install()
    {

        // Get the package object
        $this->pkg = parent::install();

        // Installing					
        $this->installOrUpgrade();
    }

    public function upgrade()
    {
        $this->pkg = parent::upgrade();
        $this->clearSampleData();
        $this->installOrUpgrade();
    }

    private function installOrUpgrade()
    {
        $this->installAttributeKeys($this->pkg);
        $this->installSampleData($this);
    }

    public function uninstall()
    {
        parent::uninstall();
        $db = \Database::get();
        $db->query("drop table TutorialDbTableDemoAttribute");
    }

    private function installAttributeKeys($pkg)
    {
        $dbTableDemoAT = AttributeType::getByHandle("db_table_demo");
        if (!$dbTableDemoAT) {
            $dbTableDemoAT = AttributeType::add("db_table_demo",
                    "Database Table Demo", $pkg);
            $cakc          = AttributeKeyCategory::getByHandle('collection');
            $cakc->associateAttributeKeyType($dbTableDemoAT);
            $uakc          = AttributeKeyCategory::getByHandle('user');
            $uakc->associateAttributeKeyType($dbTableDemoAT);
            $fakc          = AttributeKeyCategory::getByHandle('file');
            $fakc->associateAttributeKeyType($dbTableDemoAT);
        }
        $this->addPageAttributeKeys($pkg);
        $this->addUserAttributeKeys($pkg);
        $this->addFileAttributeKeys($pkg);
    }

    private function addPageAttributeKeys($pkg)
    {
        $att = CollectionAttributeKey::getByHandle("db_table_demo");
        if (!$att) {
            CollectionAttributeKey::add(
                'db_table_demo',
                array(
                'akHandle' => 'db_table_demo',
                'akName' => t('Database Table Tutorial Demo'),
                'akIsSearchable' => true), $pkg);
        }
    }

    private function addUserAttributeKeys($pkg)
    {
        $att = UserAttributeKey::getByHandle("db_table_demo");
        if (!$att) {
            UserAttributeKey::add(
                'db_table_demo',
                array(
                'akHandle' => 'db_table_demo',
                'akName' => t('Database Table Tutorial Demo'),
                'akIsSearchable' => true), $pkg);
        }
    }

    private function addFileAttributeKeys($pkg)
    {
        $att = FileAttributeKey::getByHandle("db_table_demo");
        if (!$att) {
            FileAttributeKey::add(
                'db_table_demo',
                array(
                'akHandle' => 'db_table_demo',
                'akName' => t('Database Table Tutorial Demo'),
                'akIsSearchable' => true), $pkg);
        }
    }

    private function installSampleData($pkg)
    {
        $db   = \Database::get();
        $json = new Json();
        $q    = "insert into TutorialDbTableDemoAttribute (keyID, displayTitle, data) values (?,?,?)";
        for ($i = 1; $i <= 10; $i++) {
            if ($i < 10) {
                $displayNum = "0".$i;
            } else {
                $displayNum = $i;
            }
            $item                        = array();
            // keyID
            $item[]                      = $i;
            // displayTitle
            $item[]                      = t("Display Title (Key = ".$displayNum.")");
            // data
            $itemData                    = array();
            $itemData["Department Name"] = "Facility Name (Key = ".$displayNum.")";
            $itemData["Address 1"]       = "Address 1 (Key = ".$displayNum.")";
            $itemData["Address 2"]       = "Address 2 (Key = ".$displayNum.")";
            $itemData["City"]            = "City (Key = ".$displayNum.")";
            $itemData["State"]           = "State (Key = ".$displayNum.")";
            $itemData["Zip"]             = "Zip (Key = ".$displayNum.")";
            $item[]                      = $json->encode($itemData);
            $db->query($q, $item);
        }
    }

    private function clearSampleData()
    {
        $db = \Database::get();
        $db->query("truncate table TutorialDbTableDemoAttribute");
    }
}