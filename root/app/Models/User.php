<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'company_id',
        'admin_id',
        'name',
        'email',
        'password',
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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function attendance_status()
    {
        return $this->hasMany(AttendanceStatus::class);
    }

    public function attendance_history()
    {
        return $this->hasMany(AttendanceHistory::class);
    }

    public function vacations()
    {
        return $this->hasMany(Vacation::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }



    // スタッフ検索用スコープ
    #[Scope]
    protected function searchUser(Builder $query, ?string $searchUser)
    {
        if (!empty($searchUser)) {
            return $query->where('id', 'like', '%' . $searchUser . '%')
                    ->orWhere('name', 'like', '%' . $searchUser . '%')
                    ->orWhere('email', 'like', '%' . $searchUser . '%');
        }

        return $query;
    }

    // $query->whereHas('company', function ($q) use ($selectedCompanies) {
        //     $q->whereIn('name', $selectedCompanies);
        // });

    // 会社検索用スコープ
    #[Scope]
    protected function searchCompany(Builder $query, ?array $selectedCompanies)
    {
        if (!empty($selectedCompanies)) {
            return $query->whereHas('company', function ($query) use ($selectedCompanies) {
                $query->whereIn('name', $selectedCompanies);
            });
        }

        return $query;
    }

}
