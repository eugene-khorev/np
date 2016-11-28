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
        
        $this->insert('doctor', [ 'name' => 'Dr. Gregory House', 'description' => 'Infectious Disease Specialist, Nephrologist, Head of Department of Diagnostic Medicine' ]);
        $this->insert('doctor', [ 'name' => 'Dr. Robert Chase', 'description' => 'Surgeon, Intensivist, Cardiologist, Head of Department of Diagnostic Medicine' ]);
        $this->insert('doctor', [ 'name' => 'Dr. Martha Masters', 'description' => 'Double-Ph.D. in Applied Mathematics and Art History, Medical student' ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('doctor');
    }
}
