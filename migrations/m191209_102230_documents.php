<?php

use yii\db\Migration;

/**
 * Class m191209_102230_documents
 */
class m191209_102230_documents extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('documents', [
            'id' => $this->primaryKey(),
            'iin' => $this->string()->notNull()->unique(),
            'surname' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'patronymic' => $this->string()->notNull(),
            'date_of_birth' => $this->date()->notNull(),
            'nationality' => $this->string()->notNull(),
            'registration_address' => $this->string()->notNull(),
            'doc_numbers' => $this->string()->notNull(),

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('documents');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191209_102230_documents cannot be reverted.\n";

        return false;
    }
    */
}
