<?php

/**
 * @var \bulldozer\pages\frontend\ar\Page $page
 * @var \yii\web\View $this
 * @var \bulldozer\seo\frontend\services\SeoService $seoService
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
    <h1><?= $seoService->getH1() ?></h1>

    <?=$page->body?>

    <?php if (strlen($seoService->getSeoText())): ?>
        <hr>
        <?= $seoService->getSeoText() ?>
    <?php endif ?>
</div>