<?php

use Phinx\Migration\AbstractMigration;

class Purchase extends AbstractMigration
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
        $table = $this->table('purchases', ['signed' => false]);
        $table->addColumn('payment_type_id', 'integer', ['signed' => false])
            ->addColumn('code_id', 'integer', ['signed' => false])
            ->addColumn('client_id', 'integer', ['signed' => false])
            ->addColumn('guarantor_id', 'integer', ['signed' => false])
            ->addColumn('plates', 'string')
            ->addColumn('chassis', 'string')
            ->addColumn('insurance_level', 'string')
            ->addColumn('model', 'string')
            ->addColumn('note', 'text', ['null' => true])
            ->addColumn('note_2', 'text', ['null' => true])
            ->addColumn('registered_until', 'datetime')
            ->addColumn('authorization', 'boolean')
            ->addColumn('created_at', 'datetime')
            ->addForeignKey('payment_type_id', 'codes', 'id')
            ->addForeignKey('code_id', 'codes', 'id')
            ->addForeignKey('client_id', 'clients', 'id')
            ->addForeignKey('guarantor_id', 'clients', 'id')
            ->create();
    }
}
