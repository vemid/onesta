<?php

use Phinx\Migration\AbstractMigration;

class Codes extends AbstractMigration
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
        $table = $this->table('codes', ['signed' => false]);
        $table->addColumn('code_type_id', 'integer', ['signed' => false])
            ->addColumn('parent_id', 'integer', ['signed' => false, 'null' => true, 'default' => null])
            ->addColumn('code', 'string')
            ->addColumn('name', 'string')
            ->addIndex(['code'], ['unique' => true])
            ->addForeignKey('code_type_id', 'code_types', 'id')
            ->addForeignKey('parent_id', 'codes', 'id')
            ->create();
    }
}
