<?php

namespace Savosik\OzonBundle\Pimcore;

use Pimcore\Model\DataObject;


class OzonCategoriesTreeProcessor
{


    /**
     * @throws \Exception
     */
    public function insertUpdate($ozon_categories_tree, $start_path = '')
    {
        $this->makeAllCategoriesUnpublished();

        $categories_list = [];
        $this->buildList($ozon_categories_tree, $start_path, $categories_list);

        foreach ($categories_list as $category){
            $folder = DataObject\Service::createFolderByPath($category['category_path']);

            //$folder->setPublished(true);
            $folder->setProperty('category_id', 'Text', $category['category_id']);
            $folder->save();
        }
    }


    public function makeAllCategoriesUnpublished()
    {
        $FolderList = DataObject\Folder::getList();
        var_dump($FolderList);
    }


    public function createFolder()
    {

    }


    private function buildList($categories, $path = '', &$res = array())
    {

        foreach ($categories as $category) {

            if (!empty($category['children'])) {
                $new_line = $path . $category['title'] . "/";
                $this->buildList($category['children'], $new_line, $res);
            } else {
                $arr = [
                    'category_path' => $path.$category['title'],
                    'category_id' => $category['category_id'],
                    'title' => $category['title']
                ];
                array_push($res, $arr);
            }
        }
    }

}