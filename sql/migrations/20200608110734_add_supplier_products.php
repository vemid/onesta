<?php

use Phinx\Migration\AbstractMigration;

class AddSupplierProducts extends AbstractMigration
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
        $table = $this->table('supplier_products', ['signed' => false]);
        $table->addColumn('product_id', 'integer', ['signed' => false])
            ->addColumn('supplier_id', 'integer', ['signed' => false])
            ->addColumn('avg_purchase_price', 'decimal', ['default' => null, 'null' => false, 'precision' => 9, 'scale' => 2])
            ->addColumn('retail_price', 'decimal', ['default' => null, 'null' => false, 'precision' => 9, 'scale' => 2])
            ->addColumn('created_at', 'datetime')
            ->addForeignKey('product_id', 'products', 'id')
            ->addForeignKey('supplier_id', 'suppliers', 'id')
            ->create();
    }
}
