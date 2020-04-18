<?php


use Phinx\Migration\AbstractMigration;

class InitialSetup extends AbstractMigration
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
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('users', ['signed' => false]);
        $table->addColumn('first_name', 'string')
            ->addColumn('last_name', 'string')
            ->addColumn('username', 'string')
            ->addColumn('password', 'string')
            ->addColumn('email', 'string')
            ->addColumn('avatar', 'string', ['null' => true])
            ->addColumn('gender', 'enum', ['null' => true, 'values' => ['MALE', 'FEMALE']])
            ->addColumn('is_active', 'boolean', ['default' => 0])
            ->addColumn('secret_key', 'string', ['null' => true])
            ->addColumn('last_ip', 'string', ['null' => true])
            ->addColumn('registered_datetime', 'datetime', ['null' => true])
            ->addColumn('last_visit_datetime', 'datetime', ['null' => true])
            ->create();

        $table = $this->table('roles', ['signed' => false]);
        $table ->addColumn('code', 'string')
            ->addColumn('name', 'string')
            ->addColumn('description', 'string', ['null' => true])
            ->addIndex(['code'], ['unique' => true])
            ->create();

        $table = $this->table('user_role_assignments', ['signed' => false]);
        $table->addColumn('user_id', 'integer', ['signed' => false])
            ->addColumn('role_id', 'integer', ['signed' => false])
            ->addForeignKey('user_id', 'users', 'id')
            ->addForeignKey('role_id', 'roles', 'id')
            ->create();

        $table = $this->table('audit_logs', ['signed' => false]);
        $table->addColumn('modified_entity_name', 'string')
            ->addColumn('modified_entity_id', 'integer', ['signed' => false])
            ->addColumn('operation', 'enum', ['default' => 'CREATE', 'values' => ['CREATE', 'UPDATE', 'DELETE']])
            ->addColumn('user_id', 'integer', ['signed' => false, 'null' => true])
            ->addColumn('old_data', 'blob', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::BLOB_LONG, 'null' => true])
            ->addColumn('new_data', 'blob', ['limit' =>  \Phinx\Db\Adapter\MysqlAdapter::BLOB_LONG, 'null' => true])
            ->addColumn('timestamp', 'datetime')
            ->addIndex(['timestamp', 'modified_entity_name', 'modified_entity_id', 'operation'])
            ->addForeignKey('user_id', 'users', 'id')
            ->create();
    }
}
