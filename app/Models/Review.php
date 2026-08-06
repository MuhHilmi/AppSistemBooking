<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'customer_id',
        'field_id',
        'rating',
        'comment',
        'status',
        'pending_rating',
        'pending_comment',
        'has_pending_edit',
    ];

    protected $casts = [
        'has_pending_edit' => 'boolean',
    ];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function field() {
        return $this->belongsTo(Field::class);
    }

    public function getDisplayRatingAttribute() {
        return $this->has_pending_edit ? $this->pending_rating : $this->rating;
    }

    public function getDisplayCommentAttribute() {
        return $this->has_pending_edit ? $this->pending_comment : $this->comment;
    }
}
