<?php

use Phinx\Migration\AbstractMigration;

class InsertSupplierOwner extends AbstractMigration
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
                'owner' => 1,
                'name' => 'Onesta',
                'phone_number' => '0659801845',
                'email' => 'office@servisonesta.com',
                'address' => 'Bana Ivaniša 27, Beograd',
                'postal_code' => '11400',
                'created_at' => (new DateTime())->format('Y-m-d')
            ],
        ];

        $table = $this->table('suppliers');
        $table->insert($featureRows);
        $table->saveData();
    }
}
