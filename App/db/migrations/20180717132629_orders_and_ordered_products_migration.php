<?php


use Phinx\Migration\AbstractMigration;

class OrdersAndOrderedProductsMigration extends AbstractMigration
{

    public function up()
    {
        $this->table('orders')
            ->addColumn('user_id', 'integer')
            ->addColumn('cost', 'integer')
            ->addTimestamps()
            ->addForeignKey('user_id', 'users', 'id')
            ->save();

        $this->table('ordered_products')
            ->addColumn('order_id', 'integer')
            ->addColumn('product_id', 'integer')
            ->addColumn('price', 'integer')
            ->addColumn('count', 'integer')
            ->addTimestamps()
            ->addForeignKey('order_id', 'orders', 'id')
            ->addForeignKey('product_id', 'products', 'id')
            ->save();
    }

    public function down()
    {
        $this->table('ordered_products')
            ->drop()
            ->save();

        $this->table('orders')
            ->drop()
            ->save();
    }
}
