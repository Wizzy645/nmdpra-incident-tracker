<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model {
    use HasFactory;
    protected $primaryKey = 'log_id';
    public $timestamps = false;
    protected $fillable = ['user_id', 'incident_id', 'action_type', 'action_description', 'ip_address', 'user_agent', 'timestamp', 'old_values', 'new_values'];
    protected $casts = ['timestamp' => 'datetime', 'old_values' => 'array', 'new_values' => 'array'];
}
