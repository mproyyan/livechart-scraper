<?php

namespace App\Models;

use App\Contracts\UserInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Laravel\Sanctum\NewAccessToken;

class User extends Authenticatable implements UserInterface
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
        'email_verified_at' => 'datetime',
    ];

    /**
     * Create a expirable new personal access token for the user.
     *
     * @param string $name
     * @param Carbon|null $expiredAt
     * @param array  $abilities
     *
     * @return \Laravel\Sanctum\NewAccessToken
     */
    public function createExpirableToken(string $name = 'main', ?Carbon $expiredAt, array $abilities = ['*'])
    {
        $plainTextToken = Str::random(40);

        /** @var \Laravel\Sanctum\PersonalAccessToken $token */
        $token = $this->tokens()->create([
            'name' => $name,
            'token' => hash('sha256', $plainTextToken),
            'abilities' => $abilities,
            'expired_at' => $expiredAt
        ]);

        $tokenKey = $token->getKey();

        return new NewAccessToken($token, "${tokenKey}|${plainTextToken}");
    }

    /**
     * Set the user's hashed password.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<mixed, mixed>
     */
    protected function password(): Attribute
    {
        return new Attribute(
            set: fn (string $value) => bcrypt($value)
        );
    }
}
