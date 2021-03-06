<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 19.03.18
 * Time: 17:35
 */

namespace shop\forms\manage\shop;

use shop\entities\shop\Category;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class CategoryForm extends Model
{

    public $name;
    public $title;
    public $description;
    public $parentId;
    public $meta_title;
    public $meta_description;
    public $meta_keywords;

    private $_category;


    public function __construct(Category $page = null, array $config = [])
    {
        if ($page) {
            $this->name = $page->name;
            $this->title = $page->title;
            $this->description = $page->description;
            $this->parentId = $page->parent ? $page->parent->id : null;
            $this->meta_title = $page->meta_title;
            $this->meta_description = $page->meta_description;
            $this->meta_keywords = $page->meta_keywords;

            $this->_category = $page;
        }

        parent::__construct($config);
    }

    public function rules()
    {
        return [

            [['name', 'meta_title', 'meta_description', 'meta_keywords'], 'required'],
            [['parentId'], 'integer'],
            [['name', 'title', 'meta_title', 'meta_description', 'meta_keywords'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['name'], 'unique', 'targetClass' => Category::className(),
                'filter' => $this->_category ? ['<>', 'id', $this->_category->id] : null]


        ];


    }

    public function parentCategoriesList()
    {
        return ArrayHelper::map(Category::find()->orderBy('lft')->asArray()->andWhere($this->_category ? ['<>', 'id', $this->_category->id] : null)->all(), 'id', function (array $category) {


            return ($category['depth'] > 1 ? str_repeat('-- ', $category['depth'] - 1) . ' ' : '') . $category['name'];}

        );
    }





}