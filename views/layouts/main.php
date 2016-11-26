<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>

    <div class="container">
        <header>
            <div class="container container-fluid">
                <nav class="navbar navbar-static-top navbar-inverse">
                    <div class="navbar-header"><a class="navbar-brand" href="#">Napopravku</a></div>
                </nav>
            </div>
        </header>

        <div class="container container-fluid">
            <?= $content ?>
        </div>
        
    </div>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
