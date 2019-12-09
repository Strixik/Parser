<?php


namespace app\models;


use app\models\db\Document;
use yii\base\Model;
use DateTime;

class ResultForm extends Model
{
    public $iin, $surname, $name, $patronymic, $date_of_birth, $nationality, $registration_address, $doc_numbers;
    public $id = null;


    public function rules()
    {
        return [
            [['iin', 'surname', 'name', 'patronymic', 'date_of_birth', 'nationality', 'registration_address', 'doc_numbers'], 'required'],
            [['iin', 'id'], 'integer'],
            [['surname', 'name', 'patronymic', 'date_of_birth', 'nationality', 'registration_address', 'doc_numbers'], 'safe'],
        ];
    }

    public function create()
    {
        if (!$this->validate()) {
            return false;
        }
        if (!$document = Document::findDocumentByIin($this->iin)) {
            $document = Document::createDocument();
        }
        $date = new DateTime($this->date_of_birth);
        $document->iin = $this->iin;
        $document->surname = $this->surname;
        $document->name = $this->name;
        $document->patronymic = $this->patronymic;
        $document->date_of_birth = $date->format('Y-m-d');
        $document->nationality = $this->nationality;
        $document->registration_address = $this->registration_address;
        $document->doc_numbers = $this->doc_numbers;
        $document->save();

        return $document;

    }
}