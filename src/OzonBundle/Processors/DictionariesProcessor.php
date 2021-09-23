<?php

namespace Savosik\OzonBundle\Processors;

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


        $folders = DataObject\Folder::getList();

        foreach ($folders as $folder){
            $tmp['dictionary_path'] = $folder->getFullPath();

            if(str_contains($tmp['dictionary_path'], $dictionaries_path)){
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

        foreach ($ozon_elements_set['elements'] as $element){
            $ozon_dictionary_item = new DataObject\OzonDictionaryItem();
            $ozon_dictionary_item->setKey(str_replace("/", "|",$element['value']));
            $ozon_dictionary_item->setItem_id($element['id']);
            $ozon_dictionary_item->setItem_value($element['value']);
            $ozon_dictionary_item->setItem_info($element['info']);
            $ozon_dictionary_item->setItem_picture($element['picture']);
            $ozon_dictionary_item->setParentId($folder->getId());
            $ozon_dictionary_item->setPublished(true);

            $ozon_dictionary_item->save();
        }

        if($ozon_elements_set['has_next'] == true){
            $last_element = end($ozon_elements_set['elements']);
            $last_element_id = $last_element['id'];

            $folder->setProperty('last_value_id', 'Text', $last_element_id);

            $folder->save();
        }

    }

}
