<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model {
    use HasFactory;
    protected $primaryKey = 'notification_id';
    protected $fillable = ['user_id', 'incident_id', 'channel', 'subject', 'content', 'is_read', 'sent_at'];
    protected $casts = ['is_read' => 'boolean', 'sent_at' => 'datetime'];
    public function user(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); }
}
