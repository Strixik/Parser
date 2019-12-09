<?php


namespace app\models\db\dao;
use Yii;

class DocumentQuery  extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return \app\models\db\Document[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\db\Document|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}