<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Rick
 * Date: 13.01.12
 * Time: 14:37
 * To change this template use File | Settings | File Templates.
 */

class ShopCategories extends CWidget
{
    public $listClass = 'shop-menu';

    private $_categories;

    public function run()
    {
        //$categories = Category::model()->findAll(array('order'=>'ordering'));
        $this->_categories = Category::model()->findAll(array(
        	'select'=>'id, title, lft, rgt, root, level, ordering', 
        	'order'=>'ordering, lft'
        ));;

        //$items = CmsCore::prepareTreeMenu($categories);
        $items = $this->prepareTree();

        $this->render('default', compact('items'));
    }

    private function prepareTree($level = 1, $parent = null)
    {
        $items = array();

        foreach($this->_categories as $cat) {
            /* @var Menu|NestedSetBehavior $cat */

            if ($cat->level!=$level)
                continue;

            if ($parent && !$cat->isDescendantOf($parent))
                continue;

            $item = array(
                'label'=>$cat->title,
                'url'=>array('shop/category', 'id'=>$cat->id),
            	'linkOptions'=>array('title'=>$cat->getMetaATitle())
            );

            if (!$cat->isLeaf()) {
                $item['items'] = $this->prepareTree($cat->level+1, $cat);
            }

            $items[] = $item;
        }
        return $items;
    }
}
