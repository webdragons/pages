<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $sectionsDataProvider
 * @var yii\data\ActiveDataProvider $pagesDataProvider
 * @var \bulldozer\pages\common\ar\Section $section
 */

if ($section !== null) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('pages', 'Pages'), 'url' => ['index']];

    foreach ($section->parents()->all() as $parent) {
        $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => ['view', 'id' => $parent->id]];
    }

    $this->params['breadcrumbs'][] = $section->name;
    $this->title = $section->name;
} else {
    $this->title = Yii::t('pages', 'Pages');
    $this->params['breadcrumbs'][] = $this->title;
}
?>
<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <div class="panel-actions">
                </div>

                <h2 class="panel-title"><?= Html::encode($this->title) ?></h2>
            </header>

            <div class="panel-body">
                <p>
                    <?= Html::a(Yii::t('pages', 'Create page'), ['pages/create', 'parent_id' => ArrayHelper::getValue($section, 'id', 0)], ['class' => 'btn btn-success']) ?>

                    <?php if ($section): ?>
                        <?= Html::a(Yii::t('pages', 'Create subsection'), ['create', 'parent_id' => $section->id], ['class' => 'btn btn-success']) ?>
                    <?php else: ?>
                        <?= Html::a(Yii::t('pages', 'Create section'), ['create'], ['class' => 'btn btn-success']) ?>
                    <?php endif ?>
                </p>

                <div class="table-responsive">
                    <?= GridView::widget([
                        'dataProvider' => $sectionsDataProvider,
                        'columns' => [
                            [
                                'label' => Yii::t('pages', 'Name'),
                                'content' => function ($model) {
                                    return Html::a($model->name, ['view', 'id' => $model->id]);
                                }
                            ],
                            [
                                'label' => Yii::t('pages', 'Active'),
                                'attribute' => 'active',
                                'format' => 'boolean',
                            ],
                            [
                                'label' => Yii::t('pages', 'Display order'),
                                'attribute' => 'sort',
                            ],
                            [
                                'label' => Yii::t('pages', 'Created at'),
                                'attribute' => 'created_at',
                                'format' => 'datetime',
                            ],
                            [
                                'label' => Yii::t('pages', 'Updated at'),
                                'attribute' => 'updated_at',
                                'format' => 'datetime',
                            ],
                            [
                                'label' => Yii::t('pages', 'Creator'),
                                'attribute' => 'creator.email'
                            ],
                            [
                                'label' => Yii::t('pages', 'Updater'),
                                'attribute' => 'updater.email'
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{update} {delete}',
                            ],
                        ],
                    ]); ?>
                </div>

                <?php if ($pagesDataProvider !== null): ?>
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'dataProvider' => $pagesDataProvider,
                            'columns' => [
                                [
                                    'label' => Yii::t('pages', 'Name'),
                                    'attribute' => 'name',
                                ],
                                [
                                    'label' => Yii::t('pages', 'Active'),
                                    'attribute' => 'active',
                                    'format' => 'boolean',
                                ],
                                [
                                    'label' => Yii::t('pages', 'Display order'),
                                    'attribute' => 'sort',
                                ],
                                [
                                    'label' => Yii::t('pages', 'Created at'),
                                    'attribute' => 'created_at',
                                    'format' => 'datetime',
                                ],
                                [
                                    'label' => Yii::t('pages', 'Updated at'),
                                    'attribute' => 'updated_at',
                                    'format' => 'datetime',
                                ],
                                [
                                    'label' => Yii::t('pages', 'Creator'),
                                    'attribute' => 'creator.email'
                                ],
                                [
                                    'label' => Yii::t('pages', 'Updater'),
                                    'attribute' => 'updater.email'
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{update} {delete}',
                                    'urlCreator' => function ($action, $model, $key, $index) {
                                        if ($action == 'update')
                                            return Url::to(['pages/update', 'id' => $model->id]);
                                        else if ($action == 'delete')
                                            return Url::to(['pages/delete', 'id' => $model->id]);
                                    },
                                ],
                            ],
                        ]); ?>
                    </div>
                <?php endif ?>
            </div>
        </section>
    </div>
</div>
