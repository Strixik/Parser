<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'Result';
?>
<div class="site-index">
    <?php if (Yii::$app->session->hasFlash('Update')): ?>

        <div class="alert alert-success">
            Данные обновлены.
        </div>
    <?php endif; ?>
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin(['id' => 'update-form']); ?>

            <?= $form->field($model, 'iin')->textInput() ?>
            <?= $form->field($model, 'surname')->textInput() ?>
            <?= $form->field($model, 'name')->textInput() ?>
            <?= $form->field($model, 'patronymic')->textInput() ?>
            <?= $form->field($model, 'date_of_birth')->textInput() ?>
            <?= $form->field($model, 'nationality')->textInput() ?>
            <?= $form->field($model, 'registration_address')->textInput() ?>
            <?= $form->field($model, 'doc_numbers')->textInput() ?>
            <div class="form-group">
                <?= Html::submitButton('Обновить', ['class' => 'btn btn-primary', 'name' => 'update-form']) ?>
                <?= Html::a('Вернутся', '/views',  ['class' => 'btn btn-primary', 'name' => 'update-form']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
</div>
