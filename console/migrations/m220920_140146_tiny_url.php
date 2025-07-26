<?php

use yii\db\Migration;


/**
 * Class m220920_140146_tiny_url
 */
class m220920_140146_tiny_url extends Migration
{
    const TABLE = 'tiny_url';

    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $tables = Yii::$app->db->schema->getTableNames();
        $dbType = $this->db->driverName;
        $tableOptions_mysql = "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        /* MYSQL */
        if (!in_array(self::TABLE, $tables) && $dbType == "mysql")  {
            $this->createTable('{{%'. self::TABLE . '}}', [
                'id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT',
                0 => 'PRIMARY KEY (`id`)',
                'key' => 'VARCHAR(255) NOT NULL',
                'url' => 'VARCHAR(255) NOT NULL',
                'created_at' => 'INT(11) NOT NULL',
                'updated_at' => 'INT(11) NOT NULL',
                'user_id' => 'INT(11) NULL',
                'comment' => 'TEXT NOT NULL',
                'status' => 'SMALLINT(4) NOT NULL DEFAULT \'0\'',
            ], $tableOptions_mysql);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->execute('SET foreign_key_checks = 0');
        $this->execute('DROP TABLE IF EXISTS `' . self::TABLE . '`');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
