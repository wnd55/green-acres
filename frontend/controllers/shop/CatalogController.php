<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 31.03.18
 * Time: 19:22
 */

namespace frontend\controllers\shop;


use shop\entities\shop\Characteristic;
use shop\entities\shop\product\Modification;
use shop\entities\shop\product\ValueAssignment;
use shop\forms\shop\AddToCartForm;
use shop\forms\shop\ReviewForm;
use shop\forms\shop\search\SearchForm;
use shop\forms\shop\search\ValueSearchForm;
use shop\readModels\shop\BrandReadRepository;
use shop\readModels\shop\CategoryReadRepository;
use shop\readModels\shop\ProductReadRepository;
use shop\readModels\Shop\TagReadRepository;
use shop\repositories\shop\BrandRepository;
use shop\repositories\shop\TagRepository;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CatalogController extends Controller
{

    public $layout = 'catalog';


    private $products;
    private $categories;
    private $brands;
    private $tags;



    public function __construct(
        $id,
        $module,
        ProductReadRepository $products,
        CategoryReadRepository $categories,
        BrandReadRepository $brands,
        TagReadRepository $tags,

        array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->products = $products;
        $this->categories = $categories;
        $this->brands = $brands;
        $this->tags = $tags;

    }


    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = $this->products->getAll();
        $category = $this->categories->getRoot();

        return $this->render('index', [
            'category' => $category,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */


    public function actionCategory($id)
    {

        if (!$category = $this->categories->find($id)) {

            throw new NotFoundHttpException('The requested page does not exist.');

        }

        $dataProvider = $this->products->getAllByCategory($category);

        return $this->render('category', [
            'category' => $category,
            'dataProvider' => $dataProvider]);

    }


    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */

    public function actionProduct($id)
    {

        if (!$product = $this->products->find($id)) {


            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $this->layout = 'blank';

        $cartForm = new AddToCartForm($product);
        $reviewForm = new  ReviewForm();


        return $this->render('product', [

            'product' => $product,
            'cartForm' => $cartForm,
            'reviewForm' => $reviewForm,


        ]);

    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionBrand($id)
    {
        if (!$brand = $this->brands->find($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $dataProvider = $this->products->getAllByBrand($brand);

        return $this->render('brand', [
            'brand' => $brand,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionTag($id)
    {
        if (!$tag = $this->tags->find($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $dataProvider = $this->products->getAllByTag($tag);

        return $this->render('tag', [
            'tag' => $tag,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string
     */

    public function actionSearch()
    {

        $form = new SearchForm();
        $form->load(\Yii::$app->request->queryParams);
        $form->validate();


        $dataProvider = $this->products->search($form);


        return $this->render('search', [
            'searchForm' => $form,
            'dataProvider' => $dataProvider,

        ]);


    }

}