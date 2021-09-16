<?php
namespace Savosik\OzonBundle\Pimcore;

use Pimcore\Model\DataObject;

class OzonCategoriesTreeProcessor{

    public function insertUpdate($ozon_categories_tree){

        DataObject\Folder::create(['/test1','/test2','/test3']);
    }


    public function disableAllCategories(){

    }

    public function createFolder(){

    }

}