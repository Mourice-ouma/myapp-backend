<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'name',
        'gender',
        'dateOfBirth',
        'email',
        'phoneNumber',
        'address',
        'maritalStatus',
        'baptismStatus',
        'membershipNumber',
        'joinDate',
        'activeStatus',
    ];

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_member');
    }
}
