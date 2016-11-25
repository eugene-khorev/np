<?php

use yii\db\Migration;

/**
 * Handles the creation of table `doctor`.
 */
class m161125_093113_create_doctor_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('doctor', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->string(),
            'photo' => $this->string(),
        ]);
        
        $this->insert('doctor', [ 'name' => 'doc1' ]);
        $this->insert('doctor', [ 'name' => 'doc2' ]);
        $this->insert('doctor', [ 'name' => 'doc3' ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('doctor');
    }
}
