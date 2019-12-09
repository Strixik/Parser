<?php


namespace app\models\db\dao;
use Yii;

/**
 * This is the model class for table "documents".
 *
 * @property integer $id
 * @property integer $iin
 * @property string $surname
 * @property string $name
 * @property string $patronymic
 * @property string $date_of_birth
 * @property string $nationality
 * @property string $registration_address
 * @property string $doc_numbers

 */

class Document extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'documents';
    }

    public function rules()
    {
        return [
            [['iin', 'surname', 'name', 'patronymic', 'date_of_birth', 'nationality', 'registration_address', 'doc_numbers'], 'required'],
            [['iin'], 'integer'],
            [['surname', 'name', 'patronymic', 'date_of_birth', 'nationality', 'registration_address', 'doc_numbers'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'iin' => 'ИИН',
            'surname' => 'Фамилия',
            'name' => 'Имя',
            'patronymic' => 'Отчество',
            'date_of_birth' => 'Дата рождения',
            'nationality' => 'Национальность',
            'registration_address' => 'Адрес прописки',
            'doc_numbers' => 'Номер документа',
        ];
    }

    public static function findDocumentByIin($inn)
    {
        return self::find()->where(['iin' => $inn])->one();
    }

    /**
     * @inheritdoc
     * @return \app\models\db\DocumentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\db\DocumentQuery(get_called_class());
    }
}