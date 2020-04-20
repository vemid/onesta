<?php

use Phinx\Migration\AbstractMigration;

class BankStatements extends AbstractMigration
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
        $table = $this->table('bank_statements', ['signed' => false]);
        $table->addColumn('account', 'string')
            ->addColumn('statement_number', 'string')
            ->addColumn('statement_path', 'string', ['null' => true])
            ->addColumn('file_hash', 'string', ['null' => true])
            ->addColumn('date', 'datetime')
            ->addColumn('created_at', 'datetime')
            ->create();
    }
}
