<?php
namespace Savosik\OzonBundle\Pimcore;

use Pimcore\Model\DataObject;

class OzonCategoriesTreeProcessor{

    public function insertUpdate($ozon_categories_tree){

        $folders = DataObject\Folder::getList();

        foreach ($folders as $folder){
            var_dump($folder);
        }
    }


    public function disableAllCategories(){

    }

    public function createFolder(){

    }

}