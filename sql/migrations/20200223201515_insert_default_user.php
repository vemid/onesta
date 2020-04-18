<?php

use Phinx\Migration\AbstractMigration;

class InsertDefaultUser extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $query = sprintf('INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `username`, `password`, `avatar`, `gender`, `is_active`, `secret_key`, `last_ip`, `registered_datetime`, `last_visit_datetime`) 
            VALUES (NULL, \'Vemid\', \'Vesic\', \'\' ,\'vemid\', \'%s\', NULL, NULL, \'1\', NULL, NULL, NULL, NULL);', password_hash('root', PASSWORD_BCRYPT));

        $this->execute($query);
    }
}
