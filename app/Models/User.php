<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use App\Models\Consumer;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    //  public function canAccessPanel(Panel $panel): bool
    //  {
    //      return str_ends_with($this->email, 'admin@graha.com') && $this->hasVerifiedEmail();
    //  }
     
    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function hasRole()
    {
        return $this->role;
    }

    public function consumers()
    {
        return $this->hasMany(Consumer::class);
    }
    
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}