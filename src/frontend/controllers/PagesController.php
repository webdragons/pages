<?php

namespace bulldozer\pages\frontend\controllers;

use bulldozer\App;
use bulldozer\pages\frontend\ar\Page;
use bulldozer\pages\frontend\ar\Section;
use bulldozer\seo\frontend\services\SeoService;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class PagesController
 * @package bulldozer\pages\frontend\controllers
 */
class PagesController extends Controller
{
    /**
     * @param string $param1
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionViewWithOneParam(string $param1)
    {
        $page = Page::find()->andWhere(['slug' => $param1])->one();

        if ($page) {
            return $this->viewPage($page);
        } else {
            $section = Section::find()->andWhere(['slug' => $param1])->one();

            if ($section) {
                return $this->viewSection($section);
            }
        }

        throw new NotFoundHttpException();
    }

    /**
     * @param string $param1
     * @param string $param2
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionViewWithTwoParams(string $param1, string $param2)
    {
        $section = Section::find()->andWhere(['slug' => $param1])->one();

        if ($section === null) {
            throw new NotFoundHttpException();
        }

        $page = Page::find()->andWhere(['slug' => $param2])->one();

        if ($page === null) {
            throw new NotFoundHttpException();
        }

        return $this->viewPage($page);
    }

    /**
     * @param Section $section
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    protected function viewSection(Section $section)
    {
        $pages = Page::find()
            ->andWhere(['section_id' => $section->id])
            ->addOrderBy(['sort' => SORT_ASC])
            ->all();

        $seoService = App::createObject([
            'class' => SeoService::class,
            'model' => $section,
            'defaultValues' => [
                'title' => $section->name,
                'h1' => $section->name,
            ],
        ]);

        return $this->render('view-section', [
            'section' => $section,
            'sections' => $section->children(1)->all(),
            'pages' => $pages,
            'seoService' => $seoService,
        ]);
    }

    /**
     * @param Page $page
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    protected function viewPage(Page $page)
    {
        $seoService = App::createObject([
            'class' => SeoService::class,
            'model' => $page,
            'defaultValues' => [
                'title' => $page->name,
                'h1' => $page->name,
            ],
        ]);

        return $this->render('view-page', [
            'page' => $page,
            'seoService' => $seoService,
        ]);
    }
}