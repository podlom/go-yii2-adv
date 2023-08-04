<?php

use yii\db\Migration;


/**
 * Class m230804_125934_url_hit
 */
class m230804_125934_url_hit extends Migration
{
    const TABLE = 'url_hit';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tables = Yii::$app->db->schema->getTableNames();
        $dbType = $this->db->driverName;
        $tableOptions_mysql = "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        $tableOptions_mssql = "";
        $tableOptions_pgsql = "";
        $tableOptions_sqlite = "";
        /* MYSQL */
        if (!in_array(self::TABLE, $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%'. self::TABLE . '}}', [
                    'id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'request_url' => 'VARCHAR(255) NOT NULL',
                    'url_id' => 'INT(11) UNSIGNED DEFAULT NULL',
                    'created_at' => 'INT(11) NOT NULL',
                    'updated_at' => 'INT(11) NOT NULL',
                    'user_ip' => 'VARCHAR(255) NOT NULL',
                    'user_id' => 'INT(11) NULL',
                    'server_info' => 'TEXT NOT NULL',
                ], $tableOptions_mysql);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('SET foreign_key_checks = 0');
        $this->execute('DROP TABLE IF EXISTS `' . self::TABLE . '`');
        $this->execute('SET foreign_key_checks = 1');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230804_125934_url_hit cannot be reverted.\n";

        return false;
    }
    */
}
