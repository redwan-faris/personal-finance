<?php

namespace App\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(User::class);
    }

    /**
     * Sync Roles
     * all current roles will be removed from the user and replaced by the new roles given
     *
     * @param User $user
     * @param array $data
     * @return void
     */
    private function syncRoles(User $user, array $data)
    {
        if (isset($data['roles']) && !empty($data['roles']))
        {
            $roles = is_array($data['roles']) ? $data['roles'] : [ $data['roles'] ];
            $user->syncRoles($roles);
        }
    }

    /**
     * Sync Permissions
     * all current roles will be removed from the user and replaced by the new roles given
     *
     * @param User $user
     * @param array $data
     * @return void
     */
    private function syncPermissions(User $user, array $data)
    {
        if (isset($data['permissions']) && !empty($data['permissions']))
        {
            $permissions = is_array($data['permissions']) ? $data['permissions'] : [ $data['permissions'] ];
            $user->syncPermissions($permissions);
        }
    }

    public function activateUser(User $user){
        return DB::transaction(function() use( $user){
            $user->is_active = !$user->is_active;
            $user->save();
            return $user;
        });
    }






    public function getAll(int $limit, array $filter = [])
    {
        return $this->queryBuilder($filter)->paginate($limit);
    }



    /**
     * Create new user
     */
    public function create(array $data)
    {
        return DB::transaction(function() use($data) {
            $user =  parent::create($data);
            $this->syncRoles($user, $data);
            $this->syncPermissions($user, $data);


            return $user;
        });
    }

    /**
     * Update user
     */
    public function update(Model $model ,array $data)
    {

        return DB::transaction(function() use($model, $data) {
            $user = parent::update($model, $data);
            $this->syncRoles($user, $data);
            $this->syncPermissions($user, $data);

            return $user;
        });
    }


    public function syncRolesToUser(array $roles, User $user){
        $user->syncRoles($roles);
        return $user;
    }

    public function syncPermissionsToUser(array $permissions, User $user){
        $user->syncPermissions($permissions);
        return $user;
    }

}
