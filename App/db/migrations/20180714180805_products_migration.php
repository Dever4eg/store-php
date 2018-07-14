<?php


use Phinx\Migration\AbstractMigration;

class ProductsMigration extends AbstractMigration
{
    public function change()
    {
        $this->table('products')
            ->addColumn('title', 'string')
            ->addColumn('description', 'text')
            ->addColumn('price', 'integer')
            ->addColumn('image', 'string', ['null' => true])
            ->addTimestamps()
            ->save();
    }
}
