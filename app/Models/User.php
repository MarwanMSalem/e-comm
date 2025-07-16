<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    /**
     * Get the role of the user.
     *
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function scopeFilter($query, $params)
    {
        if (!empty($params['search'])) {
            $query->where(function ($q) use ($params) {
                $q->where('name', 'like', '%' . $params['search'] . '%')
                  ->orWhere('email', 'like', '%' . $params['search'] . '%');
            });
        }
        if (!empty($params['role'])) {
            $query->where('role', $params['role']);
        }
        if (!empty($params['email'])) {
            $query->where('email', $params['email']);
        }
        return $query;
    }
}
