<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable {
    use HasFactory, Notifiable;
    protected $fillable = ['name', 'email', 'password', 'org_id', 'phone', 'role', 'status', 'mfa_secret'];
    protected $hidden = ['password', 'remember_token', 'mfa_secret'];
    protected function casts(): array {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed', 'last_login_at' => 'datetime'];
    }
    public function organisation(): BelongsTo { return $this->belongsTo(Organisation::class, 'org_id'); }
    public function incidents(): HasMany { return $this->hasMany(Incident::class, 'reporter_id'); }
    public function auditLogs(): HasMany { return $this->hasMany(AuditLog::class, 'user_id'); }
}
