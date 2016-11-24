<?php

use yii\db\Migration;

/**
 * Handles adding access_token and its expiration time
 */
class m161124_174624_add_user_access_token extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('user', 'access_token_expires', 'datetime');
        $this->addColumn('user', 'access_token', 'string');
        $this->createIndex('access_token', 'user', 'access_token', true);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropIndex('access_token', 'user');
        $this->dropColumn('user', 'access_token');
        $this->dropColumn('user', 'access_token_expires');
    }

}
