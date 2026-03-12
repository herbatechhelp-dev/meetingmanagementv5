<?php
// app/Models/ActionItem.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id',
        'assigned_to',
        'department_id',
        'title',
        'description',
        'due_date',
        'status',
        'priority',
        'completion_notes',
        'revision_notes',
        'completed_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeNeedsRevision($query)
    {
        return $query->where('status', 'needs_revision');
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())->whereNotIn('status', ['completed', 'cancelled']);
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority', 3);
    }

    public function isOverdue()
    {
        return $this->due_date->startOfDay() < now()->startOfDay() && !in_array($this->status, ['completed', 'cancelled']);
    }

    public function isCompletedLate()
    {
        return $this->status === 'completed' && $this->completed_at && $this->completed_at->startOfDay() > $this->due_date->startOfDay();
    }

public function getPriorityLabelAttribute()
{
    $priorities = [
        1 => 'Tinggi',    // 1 = Tinggi
        2 => 'Sedang',    // 2 = Sedang  
        3 => 'Rendah'     // 3 = Rendah
    ];
    
    return $priorities[$this->priority] ?? 'Tidak Diketahui';
}

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Belum Dikerjakan',
            'in_progress' => 'Sedang Dikerjakan',
            'waiting_review' => 'Menunggu Review',
            'needs_revision' => 'Perlu Revisi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];
        return $labels[$this->status] ?? $this->status;
    }

public function getPriorityBadgeAttribute()
{
    $badges = [
        1 => 'danger',   // Tinggi = merah
        2 => 'warning',  // Sedang = kuning
        3 => 'info'      // Rendah = biru
    ];
    
    return $badges[$this->priority] ?? 'secondary';
}

public function getPriorityIconAttribute()
{
    $icons = [
        1 => '🔴',  // Tinggi
        2 => '🟡',  // Sedang
        3 => '🟢'   // Rendah
    ];
    
    return $icons[$this->priority] ?? '';
}

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'secondary',
            'in_progress' => 'primary',
            'waiting_review' => 'warning', 
            'needs_revision' => 'danger',
            'completed' => 'success',
            'cancelled' => 'danger',
        ];
        return $badges[$this->status] ?? 'secondary';
    }

    public function scopeByStatus($query, $status)
{
    if ($status) {
        return $query->where('status', $status);
    }
    return $query;
}

public function files()
{
    return $this->hasMany(ActionItemFile::class);
}

}