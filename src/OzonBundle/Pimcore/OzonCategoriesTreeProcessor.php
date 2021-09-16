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
        $this->lockAllCategories($start_path);

        $categories_list = [];
        $this->buildList($ozon_categories_tree, $start_path, $categories_list);

        foreach ($categories_list as $category){
            $folder = DataObject\Service::createFolderByPath($category['category_path']);

            $folder->setLocked(false);
            $folder->setProperty('category_id', 'Text', $category['category_id']);
            $folder->save();
        }
    }


    public function lockAllCategories($start_path = null)
    {

        $folders = DataObject\Folder::getList();

        foreach ($folders as $folder){
            $folder_path = $folder->getFullPath();

            if(str_contains($folder_path, $start_path)){
                $folder->setLocked(true);
                $folder->save();
            }
        }
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