<?php

use yii\db\Migration;

class m240614_170000_create_banner_click_log_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%banner_click_log}}', [
            'id' => $this->primaryKey(),
            'url' => $this->text()->notNull(),
            'ip' => $this->string(45),
            'country' => $this->string(100),
            'city' => $this->string(100),
            'isp' => $this->string(255),
            'user_agent' => $this->text(),
            'network' => $this->string(128),
            'lang' => "char(2) COLLATE utf8mb4_bin DEFAULT 'en'",
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%banner_click_log}}');
    }
}
