<?php


use Phinx\Migration\AbstractMigration;

class OrdersProductsMigration extends AbstractMigration
{
    public function up()
    {
        $this->table('orders_products')
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
        $this->table('orders_products')
            ->drop()
            ->save();
    }
}
