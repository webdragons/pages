<?php

/**
 * @var yii\web\View $this
 * @var \bulldozer\pages\backend\forms\PageForm $model
 * @var bool $isNew
 * @var array $sections
 */

use dosamigos\ckeditor\CKEditor;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

?>
<?php $form = ActiveForm::begin(); ?>

<?php if ($model->hasErrors()): ?>
    <div class="alert alert-danger">
        <?= $form->errorSummary($model) ?>
    </div>
<?php endif ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'section_id')->dropDownList($sections, [
    'prompt' => 'Не выбрано',
]) ?>

<?= $form->field($model, 'active')->checkbox() ?>

<?= $form->field($model, 'sort')->textInput() ?>

<?= $form->field($model, 'body')->widget(CKEditor::className(), [
    'options' => ['rows' => 12],
]) ?>

<div class="form-group">
    <?= Html::submitButton($isNew ? Yii::t('pages', 'Create') : Yii::t('pages', 'Update'),
        ['class' => $isNew ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
