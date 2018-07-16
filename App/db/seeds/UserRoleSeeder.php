<?php


use Phinx\Seed\AbstractSeed;
use \App\Models\User;
use App\Models\Role;

class UserRoleSeeder extends AbstractSeed
{

    private function seedRoles($role_names)
    {
       foreach ($role_names as $name) {
           $role = new Role();
           $role->name = $name;
           $role->save();
       }
    }

    public function run()
    {
        $this->seedRoles(['admin', 'customer']);

        $user = new User();
        $user->email = 'admin@admin.com';
        $user->password = User::HashPassword('password');
        $user->role_id = Role::findByColumn('name', 'admin')[0]->id;
        $user->save();

        for($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->email = 'customer'.$i.'@user.com';
            $user->password = User::HashPassword('password');
            $user->role_id = Role::findByColumn('name', 'customer')[0]->id;
            $user->save();
        }
    }
}
