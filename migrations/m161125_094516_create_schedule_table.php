<?php

use yii\db\Migration;

/**
 * Handles the creation of table `schedule`.
 */
class m161125_094516_create_schedule_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('schedule', [
            'id' => $this->primaryKey(),
            'doctor_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'reserved_from' => $this->dateTime()->notNull(),
            'reserved_till' => $this->dateTime()->notNull(),
        ]);

        $this->createIndex('doctor_reserved_from', 'schedule', [ 'doctor_id', 'reserved_from' ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('schedule');
    }

}
