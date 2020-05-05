<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Book */
/* @var $selectCategory array */
/* @var $selectAuthor array */
/* @var $selectPublisher array */

$this->title = Yii::t(
    'app',
    'Update Book: {name}',
    [
        'name' => $model->title,
    ]
);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Books'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="book-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render(
        '_form',
        [
            'model' => $model,
            'selectCategory' => $selectCategory,
            'selectAuthor' => $selectAuthor,
            'selectPublisher' => $selectPublisher,
        ]
    ) ?>

</div>