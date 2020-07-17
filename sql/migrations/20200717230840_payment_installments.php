<?php

use Phinx\Migration\AbstractMigration;

class PaymentInstallments extends AbstractMigration
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
        $table = $this->table('payment_installments', ['signed' => false]);
        $table->addColumn('purchase_id', 'integer', ['signed' => false])
            ->addColumn('installment_date', 'datetime')
            ->addColumn('installment_amount', 'decimal', ['precision' => 9, 'scale' => 2])
            ->addColumn('payment_date', 'datetime', ['null' => true, 'default' => null])
            ->addColumn('payment_amount', 'decimal', ['precision' => 9, 'scale' => 2, 'null' => true, 'default' => null])
            ->addForeignKey('purchase_id', 'purchases', 'id')
            ->create();
    }
}
