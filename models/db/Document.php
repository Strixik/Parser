<?php

namespace app\models\db;

class Document extends \app\models\db\dao\Document
{
    public function createDocument()
    {
        return new Document();
    }
}