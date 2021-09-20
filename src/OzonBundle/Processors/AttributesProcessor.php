<?php
namespace Savosik\OzonBundle\Processors;

use Pimcore\Model\DataObject;
use Pimcore\Model\Element;


class AttributesProcessor{

    public function insertUpdate($ozon_attributes, $path = ''){

        $folder = DataObject\Folder::getByPath($path);
        $obj_parent_id = $folder->getId();


        $ozon_attributes = $ozon_attributes[0]['attributes'];

        foreach ($ozon_attributes as $ozon_attribute){

            $new_obj = new DataObject\OzonAttribute();

            $new_obj->setKey(Element\Service::getValidKey($ozon_attribute['name'], 'object'));
            $new_obj->setPublished(true);
            $new_obj->setAttr_id($ozon_attribute['id']);
            $new_obj->setName($ozon_attribute['name']);
            $new_obj->setDescription($ozon_attribute['description']);
            $new_obj->setData_type($ozon_attribute['type']);
            $new_obj->setIs_collection($ozon_attribute['is_collection']);
            $new_obj->setIs_required($ozon_attribute['is_required']);
            $new_obj->setGroup_id($ozon_attribute['group_id']);
            $new_obj->setGroup_name($ozon_attribute['group_name']);
            $new_obj->setDictionary_id($ozon_attribute['dictionary_id']);

            $new_obj->setParentId($obj_parent_id);

            $new_obj->save();
        }

    }


    public function unPublishInPath($path){
        $entries = DataObject\OzonAttribute::getList();

        foreach ($entries as $entry){
            $object_full_path = $entry->getFullPath();

            if(str_contains($object_full_path, $path)){
                $entry->setPublished(false);
                $entry->save();
            }
        }
    }

}