<?php


use Phinx\Migration\AbstractMigration;

class OrdersMigration extends AbstractMigration
{

    public function up()
    {
        $this->table('orders')
            ->addColumn('user_id', 'integer')
            ->addColumn('cost', 'integer')
            ->addTimestamps()
            ->addForeignKey('user_id', 'users', 'id')
            ->save();
    }

    public function down()
    {
        $this->table('orders')
            ->drop()
            ->save();
    }
}
