<?php

use yii\db\Migration;

/**
 * Handles the creation of table `schedule_daily`.
 */
class m161126_075707_create_schedule_daily_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('schedule_daily', [
            'id' => $this->primaryKey(),
            'doctor_id' => $this->integer()->notNull(),
            'visit_date' => $this->date()->notNull(),
            'visit_list' => $this->text()->notNull(),
        ]);
        
        $this->createIndex('doctor_visit_date', 'schedule_daily', [ 'doctor_id', 'visit_date' ], true);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('schedule_daily');
    }
}
