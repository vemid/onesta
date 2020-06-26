<?php

use Phinx\Migration\AbstractMigration;

class InsertPaymentTypeCodeType extends AbstractMigration
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
        $featureRows = [
            [
                'code' => 'PAYMENT_TYPE',
                'name' => 'Vrsta Plaćanja',
            ],
            [
                'code' => 'PURCHASE_TYPE',
                'name' => 'Vrsta Kupovine',
            ],
            [
                'code' => 'PRODUCT_TYPE',
                'name' => 'Vrsta Proizvoda',
            ],
        ];

        $table = $this->table('code_types');
        $table->insert($featureRows);
        $table->saveData();
    }
}
