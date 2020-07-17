<?php

use Phinx\Migration\AbstractMigration;

class AddRegistrations extends AbstractMigration
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
        $table = $this->table('registrations', ['signed' => false]);
        $table->addColumn('purchase_id', 'integer', ['signed' => false])
            ->addColumn('plates', 'string', ['null' => true])
            ->addColumn('chassis', 'string', ['null' => true])
            ->addColumn('insurance_level', 'string', ['null' => true])
            ->addColumn('model', 'string', ['null' => true])
            ->addColumn('registered_until', 'datetime', ['null' => true])
            ->addColumn('authorization', 'boolean', ['null' => true, 'default' => null])
            ->addColumn('note', 'text', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG, 'null' => true])
            ->addColumn('created_at', 'datetime')
            ->addForeignKey('purchase_id', 'purchases', 'id')
            ->create();
    }
}
