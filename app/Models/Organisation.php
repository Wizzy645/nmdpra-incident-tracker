<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organisation extends Model {
    use HasFactory;
    protected $primaryKey = 'org_id';
    protected $fillable = ['licence_number', 'org_name', 'org_type', 'address', 'lga', 'state', 'is_active'];
    public function users(): HasMany { return $this->hasMany(User::class, 'org_id'); }
    public function incidents(): HasMany { return $this->hasMany(Incident::class, 'org_id'); }
}
