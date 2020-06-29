<?php

use Phinx\Migration\AbstractMigration;

class AddStock extends AbstractMigration
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
        $table = $this->table('stocks', ['signed' => false]);
        $table->addColumn('supplier_product_id', 'integer', ['signed' => false])
            ->addColumn('qty', 'integer')
            ->addColumn('type', 'enum', ['null' => false, 'values' => ['INCOME', 'OUTCOME']])
            ->addColumn('purchase_price', 'decimal', ['precision' => 9, 'scale' => 2])
            ->addColumn('created_at', 'datetime')
            ->addForeignKey('supplier_product_id', 'supplier_products', 'id')
            ->create();
    }
}
