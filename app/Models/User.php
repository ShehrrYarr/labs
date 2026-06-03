<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'password_text',
        'category',
        'login_id',
        'staff_permissions',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at'  => 'datetime',
        'staff_permissions'  => 'array',
        'is_active'          => 'boolean',
    ];

    public function hasStaffPermission(string $permission): bool
    {
        if ($this->category === 'admin') {
            return true;
        }
        if ($this->category !== 'staff') {
            return false;
        }
        return in_array($permission, $this->staff_permissions ?? [], true);
    }

   public function branch()
{
    return $this->hasOne(\App\Models\Branch::class);
}
public function customer()
{
    return $this->hasOne(\App\Models\Customer::class);
}
}
