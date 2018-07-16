<?php


use Phinx\Migration\AbstractMigration;

class ProductsMigration extends AbstractMigration
{
    public function up()
    {
        $this->table('products')
            ->addColumn('title', 'string')
            ->addColumn('description', 'text')
            ->addColumn('price', 'integer')
            ->addColumn('image', 'string', ['null' => true])
            ->addTimestamps()
            ->save();
    }
    public function down()
    {
        $this->table('products')
            ->drop()
            ->save();
    }
}
