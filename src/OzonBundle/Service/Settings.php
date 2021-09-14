<?php
namespace Savosik\OzonBundle\Service;

use Pimcore\Model\DataObject;


class Settings{

    private $settings_store = [];

    public function __construct(){
        $entries = new DataObject\Settings\Listing();

        foreach ($entries as $entry){
            $this->settings_store[$entry->getSetting_key()] = $entry->getSetting_value();
        }
    }


    public function getValue($key){
        return $this->settings_store[$key] ?: null;
    }

}