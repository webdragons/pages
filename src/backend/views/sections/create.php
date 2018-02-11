<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var \bulldozer\pages\common\ar\Section $parentSection
 * @var array $sections
 * @var \bulldozer\pages\backend\forms\SectionForm $model
 * @var \bulldozer\seo\backend\services\SeoService $seoService
 */

$this->title = Yii::t('pages', 'Create section');
$this->params['breadcrumbs'][] = ['label' => 'Страницы', 'url' => ['index']];

if ($parentSection !== null) {
    foreach ($parentSection->parents()->all() as $parent) {
        $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => ['view', 'id' => $parent->id]];
    }

    $this->params['breadcrumbs'][] = ['label' => $parentSection->name, 'url' => ['view', 'id' => $parentSection->id]];
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
                <?= $this->render('_form', ['model' => $model, 'isNew' => true, 'sections' => $sections, 'seoService' => $seoService]) ?>
            </div>
        </section>
    </div>
</div>
