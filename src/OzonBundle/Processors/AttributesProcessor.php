<?php
namespace Savosik\OzonBundle\Processors;

use Pimcore\Model\DataObject\Classificationstore;


class AttributesProcessor{

    public function createClassificationStore($store_name){
        $config = Classificationstore\StoreConfig::getByName($store_name);
        if (!$config) {
            $config = new Classificationstore\StoreConfig();
            $config->setName($store_name);
            $config->save();
        }

        return $config->getId();
    }


    public function createCollection($store_id, $collection_name, $collection_description = ''){

        $config = Classificationstore\CollectionConfig::getByName($collection_name, $store_id);
        if (!$config) {
            $config = new Classificationstore\CollectionConfig();
            $config->setName($collection_name);
            $config->setDescription($collection_description);
            $config->setStoreId($store_id);
            $config->save();
        }

        return $config->getId();
    }



    public function insertUpdate($ozon_attributes, $pimcore_category){

        $ozon_category_path = $pimcore_category['full_path'];
        $ozon_category_id = $pimcore_category['ozon_category_id'];

        $category_items = explode("/", $ozon_category_path);

        $store_name = $category_items[0]; //fist element of categories three is Ozon store name (ex. Ozon/)
        $group_name = end($category_items); // last element of categories three iz Ozon group name

        //try to create classification store if not exist




    }


}