<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'Views';
?>
<div class="site-index">
    <table class="table">
        <thead>
        <tr>
            <th scope="col">ИД</th>
            <th scope="col">ИИН</th>
            <th scope="col">Фамилия</th>
            <th scope="col">Имя</th>
            <th scope="col">Отчество</th>
            <th scope="col">Дата рождения</th>
            <th scope="col">Национальность</th>
            <th scope="col">Адрес прописки</th>
            <th scope="col">Номер документов</th>
            <th scope="col">Редактировать</th>
        </tr>
        </thead>
        <tbody>
    <?php foreach ($models as $model): ?>
        <tr>
            <th scope="row"><?php echo $model->id; ?></th>
            <td><?php echo $model->iin; ?></td>
            <td><?php echo $model->surname; ?></td>
            <td><?php echo $model->name; ?></td>
            <td><?php echo $model->patronymic; ?></td>
            <td><?php $date =  new DateTime($model->date_of_birth); echo  $date->format('d-m-Y'); ?></td>
            <td><?php echo $model->nationality; ?></td>
            <td><?php echo $model->registration_address; ?></td>
            <td><?php echo $model->doc_numbers; ?></td>
            <td style="text-align: center; font-size: 1.5em;"><a href="/view/<?php echo $model->id; ?>"><i class="fa fa-edit"></i></a></td>
        </tr>
    <?php endforeach;?>
        </tbody>
    </table>
</div>
