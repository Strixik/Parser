<?php

namespace app\models;

use app\models\db\Document;
use Yii;
use yii\base\Model;
use app\components\ParseUrl;

/**
 * ContactForm is the model behind the contact form.
 */
class IinForm extends Model
{
    public $iin;



    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['iin'], 'required'],
            [['iin'], 'number'],
            [['iin'], 'string', 'min' => 12],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'iin' => 'ИИН',
        ];
    }


    public function save()
    {
        if ($this->validate()) {
            if($document = Document::findDocumentByIin($this->iin)){
                return $document;
            }
            $component = new ParseUrl();
            return  $component->loadUsingCurl(Yii::$app->params['urlParse'], $this->iin);
        }
        return false;
    }
}
