<?php


use Phinx\Migration\AbstractMigration;

class ProductsMigration extends AbstractMigration
{
    public function change()
    {
        $this->table('products')
            ->addColumn('title', 'string', ['null' => false])
            ->addColumn('description', 'text', ['null' => false])
            ->addColumn('price', 'integer', ['null' => false])
            ->addColumn('image', 'string')
            ->addTimestamps()
            ->save();
    }
}
