<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Incident extends Model {
    use HasFactory;
    protected $primaryKey = 'incident_id';
    protected $fillable = ['reporter_id', 'org_id', 'incident_ref', 'incident_type', 'severity', 'status', 'description', 'location_address', 'latitude', 'longitude', 'occurred_at', 'compliance_deadline_at', 'resolved_at', 'resolution_notes', 'media_evidence'];
    protected $casts = ['occurred_at' => 'datetime', 'compliance_deadline_at' => 'datetime', 'resolved_at' => 'datetime', 'reported_at' => 'datetime', 'media_evidence' => 'array', 'is_compliant' => 'boolean'];
    
    public function reporter(): BelongsTo { return $this->belongsTo(User::class, 'reporter_id'); }
    public function organisation(): BelongsTo { return $this->belongsTo(Organisation::class, 'org_id'); }
    public function casualty(): HasOne { return $this->hasOne(Casualty::class, 'incident_id'); }
    
    protected static function booted() {
        static::creating(function ($incident) {
            if (empty($incident->incident_ref)) {
                $year = now()->year;
                $count = static::whereYear('created_at', $year)->count() + 1;
                $incident->incident_ref = sprintf('NMD-%d-%05d', $year, $count);
            }
            if (empty($incident->compliance_deadline_at)) {
                $hours = in_array($incident->severity, ['critical', 'fatal']) ? 24 : 72;
                $incident->compliance_deadline_at = now()->addHours($hours);
            }
        });
    }
}
