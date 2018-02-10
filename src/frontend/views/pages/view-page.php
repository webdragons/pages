<?php

/**
 * @var \bulldozer\pages\frontend\ar\Page $page
 * @var \yii\web\View $this
 */

if ($page->section) {
    $parents = $page->section->parents()->all();
    $parents[] = $page->section;

    foreach ($parents as $parent) {
        $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => $parent->viewUrl];
    }
}

$this->params['breadcrumbs'][] = $page->name;
?>
<div>
    <h1 class="page-title"><?= $page->name ?></h1>

    <?=$page->body?>
</div>