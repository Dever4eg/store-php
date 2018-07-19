<?php


use Phinx\Migration\AbstractMigration;

class OrderedProductsMigration extends AbstractMigration
{
    public function up()
    {
        $this->table('ordered_products')
            ->addColumn('order_id', 'integer')
            ->addColumn('product_id', 'integer')
            ->addColumn('price', 'integer')
            ->addColumn('count', 'integer')
            ->addForeignKey('order_id', 'orders', 'id')
            ->addForeignKey('product_id', 'products', 'id')
            ->save();
    }

    public function down()
    {
        $this->table('ordered_products')
            ->drop()
            ->save();
    }
}
