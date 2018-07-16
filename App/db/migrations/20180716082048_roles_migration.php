<?php


use Phinx\Migration\AbstractMigration;

class RolesMigration extends AbstractMigration
{

    public function up()
    {
        $this->table('roles')
            ->addColumn('name', 'string')
            ->addTimestamps()
            ->save();
    }

    public function down()
    {
        $this->table('roles')
            ->drop()
            ->save();
    }
}
