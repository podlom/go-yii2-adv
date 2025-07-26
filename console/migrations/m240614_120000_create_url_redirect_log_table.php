<?php

use yii\db\Migration;

class m240614_120000_create_url_redirect_log_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%url_redirect_log}}', [
            'id' => $this->primaryKey(),
            'url' => $this->text()->notNull(),
            'ip' => $this->string(45),
            'country' => $this->string(100),
            'city' => $this->string(100),
            'isp' => $this->string(255),
            'user_agent' => $this->text(),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%url_redirect_log}}');
    }
}
