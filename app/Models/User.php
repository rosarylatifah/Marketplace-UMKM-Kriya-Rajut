<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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

    /**
     * ALUR RESET PASSWORD ADMIN
     * Mengubah rute link reset password bawaan Laravel menjadi rute admin lu
     */
    public function sendPasswordResetNotification($token)
    {
        $url = route('admin.password.reset', [
            'token' => $token,
            'email' => $this->email
        ]);

        // Langsung tembak teks suratnya ke laravel.log secara manual!
        \Illuminate\Support\Facades\Log::info("\n" . str_repeat('=', 50) . "\n" .
            "LINK RESET PASSWORD ADMIN NAMONIC\n" .
            "Kirim Ke: " . $this->email . "\n" .
            "Silakan copy-paste link di bawah ini.\n" .
            $url . "\n" .
            str_repeat('=', 50) . "\n"
        );
    }
}
