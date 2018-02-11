<?php

namespace bulldozer\pages\backend\controllers;

use bulldozer\App;
use bulldozer\pages\backend\services\SectionsService;
use bulldozer\pages\common\ar\Page;
use bulldozer\pages\common\ar\Section;
use bulldozer\seo\backend\services\SeoService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class SectionsController
 * @package bulldozer\pages\backend\controllers
 */
class SectionsController extends Controller
{
    /**
     * @var SectionsService
     */
    private $sectionsService;

    /**
     * @var SeoService
     */
    private $seoService;

    /**
     * SectionsController constructor.
     * @param string $id
     * @param $module
     * @param SectionsService $sectionsService
     * @param SeoService $seoService
     * @param array $config
     */
    public function __construct(string $id, $module, SectionsService $sectionsService, SeoService $seoService, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->sectionsService = $sectionsService;
        $this->seoService = $seoService;
    }

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'create',
                            'update',
                            'delete',
                            'view'
                        ],
                        'allow' => true,
                        'roles' => ['pages_manage'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        $query = Section::find()
            ->roots()
            ->with(['creator', 'updater']);

        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = App::createObject([
            'class' => ActiveDataProvider::class,
            'query' => $query
        ]);

        /** @var $pagesDataProvider $dataProvider */
        $pagesDataProvider = App::createObject([
            'class' => ActiveDataProvider::class,
            'query' => Page::find()->where(['section_id' => 0])->with(['creator', 'updater'])
        ]);

        return $this->render('view', [
            'sectionsDataProvider' => $dataProvider,
            'pagesDataProvider' => $pagesDataProvider,
            'section' => null,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionView(int $id)
    {
        $section = Section::findOne($id);

        if ($section === null) {
            throw new NotFoundHttpException();
        }

        /** @var ActiveDataProvider $dataProvider */
        $sectionsDataProvider = App::createObject([
            'class' => ActiveDataProvider::class,
            'query' => $section->children(1)->with(['creator', 'updater'])
        ]);

        /** @var $pagesDataProvider $dataProvider */
        $pagesDataProvider = App::createObject([
            'class' => ActiveDataProvider::class,
            'query' => Page::find()->where(['section_id' => $section->id])->with(['creator', 'updater'])
        ]);

        return $this->render('view', [
            'section' => $section,
            'sectionsDataProvider' => $sectionsDataProvider,
            'pagesDataProvider' => $pagesDataProvider,
        ]);
    }

    /**
     * @param int|null $parent_id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \bulldozer\pages\backend\services\SectionSaveException
     */
    public function actionCreate(int $parent_id = 0)
    {
        $parentSection = null;

        if ($parent_id !== 0) {
            $parentSection = Section::findOne($parent_id);

            if ($parentSection === null) {
                throw new NotFoundHttpException();
            }
        }

        $model = $this->sectionsService->getForm($parent_id);
        $seoForm = $this->seoService->getForm();

        if ($model->load(App::$app->request->post()) && $model->validate() && $seoForm->load(App::$app->request->post()) && $seoForm->validate()) {
            $section = $this->sectionsService->save($model);
            $this->seoService->save($section);

            App::$app->getSession()->setFlash('success', Yii::t('pages', 'Section successful created'));

            return $this->redirect(['view', 'id' => $section->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'parentSection' => $parentSection,
            'sections' => $this->sectionsService->getSectionsTree(),
            'seoService' => $this->seoService,
        ]);
    }

    /**
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \bulldozer\pages\backend\services\SectionSaveException
     */
    public function actionUpdate(int $id)
    {
        $section = Section::findOne($id);

        if ($section == null) {
            throw new NotFoundHttpException();
        }

        $model = $this->sectionsService->getForm(0, $section);

        $this->seoService->load($section);
        $seoForm = $this->seoService->getForm();

        if ($model->load(App::$app->request->post()) && $model->validate() && $seoForm->load(App::$app->request->post()) && $seoForm->validate()) {
            $section = $this->sectionsService->save($model, $section);
            $this->seoService->save($section);

            App::$app->getSession()->setFlash('success', Yii::t('pages', 'Section successful updated'));

            return $this->redirect(['view', 'id' => $section->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'section' => $section,
            'sections' => $this->sectionsService->getSectionsTree(),
            'seoService' => $this->seoService,
        ]);
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionDelete(int $id)
    {
        $section = Section::findOne($id);

        if ($section == null) {
            throw new NotFoundHttpException();
        }

        $parent = null;

        if (!$section->isRoot()) {
            $parent = $section->parents(1)->one();
        }

        $section->deleteWithChildren();

        App::$app->getSession()->setFlash('success', Yii::t('pages', 'Section successful deleted'));

        if ($parent) {
            return $this->redirect(['view', 'id' => $parent->id]);
        } else {
            return $this->redirect(['index']);
        }
    }
}