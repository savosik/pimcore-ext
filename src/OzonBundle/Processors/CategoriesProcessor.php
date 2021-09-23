<?php

namespace Savosik\OzonBundle\Processors;

use Pimcore\Model\DataObject;


class CategoriesProcessor
{

    public function getCategories($start_path): array
    {
        $folders = DataObject\Folder::getList(
            [
                'objectTypes' => [DataObject::OBJECT_TYPE_FOLDER]
            ]
        );

        $res = [];
        foreach ($folders as $folder) {

            $childs_amount = $folder->getChildAmount();
            $full_path = $folder->getFullPath();
            $is_locked = $folder->isLocked();
            $ozon_category_id = $folder->getProperty('category_id');

            if (str_contains($full_path, $start_path) && !$is_locked && $childs_amount == 0) {
                array_push($res,
                    ['full_path' => $full_path, 'ozon_category_id' => $ozon_category_id]
                );
            }
        }

        return $res;
    }

    /**
     * @throws \Exception
     */
    public function insertUpdateFromOzonThree($ozon_categories_tree, $start_path = '')
    {
        $categories_list = [];
        $this->buildList($ozon_categories_tree, $start_path, $categories_list);

        foreach ($categories_list as $category) {
            $folder = DataObject\Service::createFolderByPath($category['category_path']);

            $folder->setLocked(false);
            $folder->setProperty('category_id', 'Text', $category['category_id']);
            $folder->save();
        }
    }


    private function buildList($categories, $path = '', &$res = array())
    {
        foreach ($categories as $category) {

            if (!empty($category['children'])) {
                $new_line = $path . $category['title'] . "/";

                //recursive call
                $this->buildList($category['children'], $new_line, $res);
            } else {
                $arr = [
                    'category_path' => $path . $category['title'],
                    'category_id' => $category['category_id'],
                    'title' => $category['title']
                ];
                array_push($res, $arr);
            }
        }
    }


    public function lockAllCategories($start_path = null)
    {
        $folders = DataObject\Folder::getList(
            [
                'objectTypes' => [DataObject::OBJECT_TYPE_FOLDER]
            ]
        );

        foreach ($folders as $folder) {
            $folder_path = $folder->getFullPath();


            if (str_contains($folder_path, $start_path)) {
                $folder->setLocked(true);
                $folder->save();
            }
        }
    }

}