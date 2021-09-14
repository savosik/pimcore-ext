<?php
namespace Savosik\OzonBundle\Helpers;

use Pimcore\Model\DataObject;

class SettingsHelper{

    private $settings_store = [];

    public function __construct(){
        $entries = new DataObject\Settings\Listing();

        foreach ($entries as $entry){
            $this->settings_store[$entry->getSetting_key()] = $entry->getSetting_value();
        }
    }

    public function getByKey($key){
        return $this->settings_store[$key] ?: null;
    }
}