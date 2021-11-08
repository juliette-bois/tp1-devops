<?php

namespace App;

use Exception;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class User extends Model
{
    use Authenticatable;

    /**
     * Permissions of the users on each budgets
     * Loaded when we call method getPermsForBudget() or getPermsForBudgetsInGroup()
     */
    protected $permissions = array();

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function groups()
    {
        return $this->belongsToMany('App\Group');
    }

    /**
     * Return true if the user is in the specified group
     *
     * @param Group $group
     * @return bool
     * @throws Exception
     */
    public function isInGroup(Group $group)
    {

        if(empty($group->id)){
            throw new Exception('$group->id empty');
        }

        if(empty($this->attributes['roleInGroups'][$group->id])){
            $this->attributes['roleInGroups'][$group->id] = DB::table('group_user')->select('role')
                ->where('group_id', $group->id)->where('user_id', $this->id)
                ->get()->first();

            return is_null($this->attributes['roleInGroups'][$group->id]) ? false : true;

        }
        return is_null($this->attributes['roleInGroups'][$group->id]) ? false : true;
    }

    /**
     * Return the user role in a specific group
     *
     * @param Group $group
     * @return string
     * @throws Exception
     */
    public function getRoleForGroup(Group $group)
    {

        if(!$this->isInGroup($group)){
            throw new Exception('User is not in group');
        }

        return $this->attributes['roleInGroups'][$group->id]->role;

    }

    /**
     * Return if the user is a group admin
     *
     * @param $group
     * @return bool
     * @throws Exception
     */
    public function isAdmin($group) {

        if ($this->getRoleForGroup($group) == 'admin') {
            return true;
        }

        return false;
    }

    /**
     * Get permission for a user on a specific budget
     *
     * @param Budget $budget
     * @return string
     */
    public function getPermsForBudget(Budget $budget)
    {
        return empty($this->getAllDefinedPerms()[$budget->id]) ? 'none' : $this->getAllDefinedPerms()[$budget->id];
    }

    /**
     * Check if a user has permission to view or edit a budget
     * If user has edit permission he automatically had read permission.
     *
     * @param $permToCheck
     * @param Budget $budget
     * @return bool
     */
    public function hasPerm($permToCheck, Budget $budget){

        $perm = $this->getPermsForBudget($budget);

        if($permToCheck == Permission::PERM_EDIT){
            return ($perm == Permission::PERM_EDIT) ? true : false;
        }

        if($permToCheck == Permission::PERM_VIEW){
            return ($perm == Permission::PERM_EDIT || Permission::PERM_VIEW) ? true : false;
        }

        return false;
    }

    /**
     * Get all permissions for a users on all groups and all budgets
     *
     * @return array[budget_id]
     */
    public function getAllDefinedPerms()
    {

        if(empty($this->permissions)){
            $permsFromDb = DB::table('permissions')->select('perm', 'budget_id')
                ->where('user_id', $this->id)
                ->get();

            foreach ($permsFromDb as $perm){
                $this->permissions[$perm->budget_id] = $perm->perm;
            }

            return $this->permissions;

        }

        return $this->permissions;

    }

}
