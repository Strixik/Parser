<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'Result';
?>
<div class="site-index">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin(['id' => 'result-form']); ?>

            <?= $form->field($model, 'iin')->textInput(['readonly'=> true]) ?>
            <?= $form->field($model, 'surname')->textInput(['readonly'=> true]) ?>
            <?= $form->field($model, 'name')->textInput(['readonly'=> true]) ?>
            <?= $form->field($model, 'patronymic')->textInput(['readonly'=> true]) ?>
            <?= $form->field($model, 'date_of_birth')->textInput(['readonly'=> true]) ?>
            <?= $form->field($model, 'nationality')->textInput(['readonly'=> true]) ?>
            <?= $form->field($model, 'registration_address')->textInput(['readonly'=> true]) ?>
            <?= $form->field($model, 'doc_numbers')->textInput(['readonly'=> true]) ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'result-form']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
</div>
