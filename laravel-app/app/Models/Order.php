<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'user_id',
    ];

    // Order statuses
    public const STATUS_DRAFT = 'draft';

    public const STATUS_PLACED = 'placed';

    public const STATUS_PAID = 'paid';

    public const STATUS_FULFILLED = 'fulfilled';

    public const STATUS_CANCELLED = 'cancelled';

    // Relationships
    public function lines()
    {
        return $this->hasMany(OrderLine::class);
    }

    public function logs()
    {
        return $this->hasMany(OrderStatusLog::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updateStatus(string $newStatus, ?string $reason = null, ?int $actorId = null)
    {
        $oldStatus = $this->status;
        $this->update(['status' => $newStatus]);

        $this->logs()->create([
            'from_status' => $oldStatus,
            'to_status' => $newStatus,
            'actor_id' => $actorId,
            'reason' => $reason,
        ]);
    }
}
