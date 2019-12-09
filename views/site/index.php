<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <?php if (Yii::$app->session->hasFlash('FormSubmitted')): ?>

        <div class="alert alert-danger">
            Данный инн не найден или не прошла проверку капча..
        </div>
    <?php endif; ?>
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin(['id' => 'iin-form']); ?>

            <?= $form->field($model, 'iin')->textInput(['autofocus' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton('Получить', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
</div>
