<?php

namespace bulldozer\pages\backend\controllers;

use bulldozer\App;
use bulldozer\pages\backend\services\PagesService;
use bulldozer\pages\backend\services\SectionsService;
use bulldozer\pages\common\ar\Page;
use bulldozer\pages\common\ar\Section;
use bulldozer\seo\backend\services\SeoService;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class PagesController
 * @package bulldozer\pages\backend\controllers
 */
class PagesController extends Controller
{
    /**
     * @var SectionsService
     */
    private $sectionsService;

    /**
     * @var PagesService
     */
    private $pagesService;

    /**
     * @var SeoService
     */
    private $seoService;

    /**
     * PagesController constructor.
     * @param string $id
     * @param $module
     * @param SectionsService $sectionsService
     * @param PagesService $pagesService
     * @param SeoService $seoService
     * @param array $config
     */
    public function __construct(
        string $id,
        $module,
        SectionsService $sectionsService,
        PagesService $pagesService,
        SeoService $seoService,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);

        $this->sectionsService = $sectionsService;
        $this->pagesService = $pagesService;
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
                            'create',
                            'update',
                            'delete',
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
     * @param int $parent_id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionCreate(int $parent_id = 0)
    {
        if ($parent_id != 0) {
            $section = Section::findOne($parent_id);

            if ($section == null) {
                throw new NotFoundHttpException();
            }
        } else {
            $section = null;
        }

        $model = $this->pagesService->getForm($parent_id);
        $seoForm = $this->seoService->getForm();

        if ($model->load(App::$app->request->post()) && $model->validate() && $seoForm->load(App::$app->request->post()) && $seoForm->validate()) {
            $page = $this->pagesService->save($model);
            $this->seoService->save($page);

            App::$app->getSession()->setFlash('success', Yii::t('pages', 'Page successful created'));

            if ($page->section_id != 0) {
                return $this->redirect(['sections/view', 'id' => $page->section_id]);
            } else {
                return $this->redirect(['sections/index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'section' => $section,
            'sections' => $this->sectionsService->getSectionsTree(),
            'seoService' => $this->seoService,
        ]);
    }

    /**
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionUpdate(int $id)
    {
        $page = Page::findOne($id);

        if ($page == null) {
            throw new NotFoundHttpException();
        }

        $model = $this->pagesService->getForm(0, $page);

        $this->seoService->load($page);
        $seoForm = $this->seoService->getForm();

        if ($model->load(App::$app->request->post()) && $model->validate() && $seoForm->load(App::$app->request->post()) && $seoForm->validate()) {
            $page = $this->pagesService->save($model, $page);
            $this->seoService->save($page);

            App::$app->getSession()->setFlash('success', Yii::t('pages', 'Page successful updated'));

            if ($page->section_id != 0) {
                return $this->redirect(['sections/view', 'id' => $page->section_id]);
            } else {
                return $this->redirect(['sections/index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'section' => $page->section,
            'sections' => $this->sectionsService->getSectionsTree(),
            'seoService' => $this->seoService,
        ]);
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete(int $id)
    {
        $model = Page::findOne($id);

        if ($model == null) {
            throw new NotFoundHttpException();
        }

        $section_id = $model->section_id;

        $model->delete();

        App::$app->getSession()->setFlash('success', Yii::t('pages', 'Page successful deleted'));

        if ($section_id != 0) {
            return $this->redirect(['sections/view', 'id' => $section_id]);
        } else {
            return $this->redirect(['sections/index']);
        }
    }
}