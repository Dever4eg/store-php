<?php


use Phinx\Migration\AbstractMigration;

class UsersMigration extends AbstractMigration
{
    public function up()
    {
        $this->table('users')
            ->addColumn('email', 'string')
            ->addColumn('password', 'string')
            ->addColumn('role_id', 'integer')
            ->addTimestamps()
            ->addForeignKey('role_id', 'roles', 'id')
            ->save();
    }

    public function down()
    {
        $this->table('users')
            ->drop()
            ->save();
    }
}
