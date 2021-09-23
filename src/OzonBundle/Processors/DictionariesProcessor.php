<?php

namespace Savosik\OzonBundle\Processors;

use Pimcore\DataObject\GridColumnConfig\Operator\PHP;
use Pimcore\Model\DataObject;


class DictionariesProcessor
{
    public function getDictionaryToProcess($dictionaries_path){

        $res = [
            "dictionary_path" => "",
            "category_id" =>  0,
            "attribute_id" => 0,
            "last_modified" => time(),
            "last_value_id" => 0
        ];


        $folders = DataObject\Folder::getList(
            [
                'objectTypes' => [DataObject::OBJECT_TYPE_FOLDER]
            ]
        );



        foreach ($folders as $folder){

            $tmp['dictionary_path'] = $folder->getPath();
            echo $folder->getPath().PHP_EOL;

            if(str_contains($tmp['dictionary_path'], $dictionaries_path) && $tmp['dictionary_path'] != $dictionaries_path){
                $tmp['category_id'] = $folder->getProperty('can_get_with_category');
                $tmp['attribute_id'] = $folder->getProperty('can_get_with_attribute');

                $tmp['last_modified'] = $folder->getProperty('last_modified');
                $tmp['last_value_id'] = $folder->getProperty('last_value_id');

                //get oldest dictionary to process
                if($tmp['last_modified'] < $res["last_modified"]){
                    $res = $tmp;
                }
                // ops... we found dictionary with not complete getting of elements
                if(intval($tmp['last_value_id']) != 0){
                    $res = $tmp;
                    break;
                }
            }
        }

        return $res;
    }


    public function addElementsToDictionary($dictionary_path, $ozon_elements_set){
        $folder = DataObject\Folder::getByPath($dictionary_path);
        $folder->setProperty('last_modified','text', time());

        $folder_path = $folder->getRealFullPath();

        foreach ($ozon_elements_set['elements'] as $element){
            $key = trim(str_replace("/", "|", $element['value']));

            $isset_obj = DataObject::getByPath($folder_path."/".$key);

            if(!$isset_obj){
                $dictionary_item = new DataObject\OzonDictionaryItem();
                $dictionary_item->setKey($key);
                $dictionary_item->setItem_id($element['id']);
                $dictionary_item->setItem_value($element['value']);
                $dictionary_item->setItem_info($element['info']);
                $dictionary_item->setItem_picture($element['picture']);
                $dictionary_item->setParentId($folder->getId());
                $dictionary_item->setPublished(true);
                $dictionary_item->save();
            }

        }

        if($ozon_elements_set['has_next'] == true){
            $last_element = end($ozon_elements_set['elements']);
            $last_element_id = $last_element['id'];

            $folder->setProperty('last_value_id', 'Text', $last_element_id);
            $folder->save();
        }else{
            $folder->setProperty('last_value_id', 'Text', 0);
            $folder->save();
        }

    }

}
