<?php

use Phinx\Migration\AbstractMigration;

class InserRoleAndAssigments extends AbstractMigration
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
        $query = sprintf('INSERT INTO `roles` (`id`, `code`, `name`, `description`)
            VALUES (NULL, \'GUEST\', \'Guest\', \'\'),
                    (NULL, \'ADMIN\', \'Admin\', \'\')');

        $this->execute($query);

        $queryUSer = sprintf('SELECT * FROM users WHERE username = \'%s\' LIMIT 1;', 'Vemid');
        $result = $this->fetchAll($queryUSer);
        $resultUser = array_pop($result);

        $queryRole = sprintf('SELECT * FROM roles WHERE code = \'%s\' LIMIT 1;', 'ADMIN');
        $result = $this->fetchAll($queryRole);
        $resultRole = array_pop($result);


        $query = sprintf('INSERT INTO `user_role_assignments` (`id`, `user_id`, `role_id`)
            VALUES (NULL, \'%s\', \'%s\')', $resultUser['id'], $resultRole['id']);

        $this->execute($query);
    }
}
