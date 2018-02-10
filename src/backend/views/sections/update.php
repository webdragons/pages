<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var \bulldozer\pages\common\ar\Section $section
 * @var \bulldozer\pages\backend\forms\SectionForm $model
 * @var array $sections
 */

$this->params['breadcrumbs'][] = ['label' => Yii::t('pages', 'Pages'), 'url' => ['index']];

foreach ($section->parents()->all() as $parent) {
    $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => ['view', 'id' => $parent->id]];
}

$this->params['breadcrumbs'][] = ['label' => $section->name, 'url' => ['view', 'id' => $section->id]];
$this->params['breadcrumbs'][] = Yii::t('pages', 'Update');

$this->title = Yii::t('pages', 'Update section: {name}', ['name' => $section->name]);
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
                <?= $this->render('_form', ['model' => $model, 'isNew' => false, 'sections' => $sections]) ?>
            </div>
        </section>
    </div>
</div>
