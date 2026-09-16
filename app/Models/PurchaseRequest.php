<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'kode',
        'title',
        'description',
        'category_id',
        'requester_id',
        'approved_by',
        'status',
        'priority',
        'total_estimated',
        'total_actual',
        'needed_date',
        'purchased_date',
        'received_date',
        'rejection_reason',
        'notes',
    ];

    protected $casts = [
        'needed_date' => 'date',
        'purchased_date' => 'date',
        'received_date' => 'date',
    ];

    protected static function boot(): void
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->kode)) {
                $year = date('Y');
                $count = static::whereYear('created_at', $year)->count() + 1;
                $model->kode = sprintf('PR-%s-%03d', $year, $count);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ItemCategory::class, 'category_id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class, 'request_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class, 'request_id');
    }
}
