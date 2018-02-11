<?php

/**
 * @var \yii\web\View $this
 * @var \bulldozer\pages\frontend\ar\Section $section
 * @var \bulldozer\pages\frontend\ar\Section[] $sections
 * @var \bulldozer\pages\frontend\ar\Page[] $pages
 * @var \bulldozer\seo\frontend\services\SeoService $seoService
 */

if ($section !== null) {
    foreach ($section->parents()->all() as $parent) {
        $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => $parent->viewUrl];
    }

    $this->params['breadcrumbs'][] = $section->name;
}
?>
<div>
    <?php if ($section !== null): ?>
        <h1 class="page-title"><?= $seoService->getH1() ?></h1>
    <?php endif ?>

    <?php if (count($sections) > 0): ?>
        <?php foreach ($sections as $section): ?>
            <div class="row">
                <div class="col-md-12">
                    <a href="<?=$section->viewUrl?>">
                        <h2><?=$section->name?></h2>
                    </a>
                </div>
            </div>
        <?php endforeach ?>
    <?php endif ?>

    <?php if (count($pages) > 0): ?>
        <?php foreach ($pages as $page): ?>
            <div class="row">
                <div class="col-md-12">
                    <a href="<?=$page->viewUrl?>">
                        <h2><?=$page->name?></h2>
                    </a>
                </div>
            </div>
        <?php endforeach ?>
    <?php endif ?>

    <?php if (strlen($seoService->getSeoText())): ?>
        <hr>
        <?= $seoService->getSeoText() ?>
    <?php endif ?>
</div>
