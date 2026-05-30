<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Casualty extends Model {
    use HasFactory;
    protected $primaryKey = 'casualty_id';
    protected $fillable = ['incident_id', 'fatalities_count', 'injuries_count', 'evacuations_count', 'environmental_damage_est_usd', 'property_damage_est_usd', 'spill_volume_barrels', 'affected_area_sqkm'];
    protected $casts = ['environmental_damage_est_usd' => 'decimal:2', 'property_damage_est_usd' => 'decimal:2'];
    public function incident(): BelongsTo { return $this->belongsTo(Incident::class, 'incident_id'); }
}
