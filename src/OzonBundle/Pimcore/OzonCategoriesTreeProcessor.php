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

        $categories_list = [];
        $this->buildList($ozon_categories_tree, $start_path, $categories_list);

        foreach ($categories_list as $category){
            DataObject\Service::createFolderByPath($category['category_path']);

            $object_folder = DataObject\Folder::getByPath($category['category_path']);
            $object_folder->setProperty('category_id', 'Text', $category['category_id']);
            $object_folder->save();
        }
    }


    public function disableAllCategories()
    {

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