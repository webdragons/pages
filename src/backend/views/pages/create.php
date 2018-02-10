<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var \bulldozer\pages\backend\forms\PageForm $model
 * @var \bulldozer\pages\common\ar\Section $section
 * @var array $sections
 */

$this->title = Yii::t('pages', 'Create page');
$this->params['breadcrumbs'][] = ['label' => Yii::t('pages', 'Pages'), 'url' => ['index']];

if ($section) {
    foreach ($section->parents()->all() as $parent) {
        $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => ['view', 'id' => $parent->id]];
    }

    $this->params['breadcrumbs'][] = ['label' => $section->name, 'url' => ['view', 'id' => $section->id]];
}

$this->params['breadcrumbs'][] = $this->title;
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
                <?= $this->render('_form', ['model' => $model, 'isNew' => true, 'sections' => $sections]) ?>
            </div>
        </section>
    </div>
</div>
