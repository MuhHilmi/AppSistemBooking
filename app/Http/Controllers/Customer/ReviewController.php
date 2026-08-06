<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Field;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function edit(Field $field) {
        $customerId = Auth::guard('customer')->id();

        $this->ensureEligible($field->id, $customerId);
        $review = Review::where('customer_id', $customerId)->where('field_id', $field->id)->first();

        return view('customer.reviews.edit', compact('field', 'review'));
    }

    public function store(Request $request, Field $field) {
        $customerId = Auth::guard('customer')->id();

        $this->ensureEligible($field->id, $customerId);
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $review = Review::where('customer_id', $customerId)->where('field_id', $field->id)->first();

        if (!$review) {
            Review::create([
                'customer_id' => $customerId,
                'field_id' => $field->id,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'status' => 'pending',
            ]);
        } elseif ($review->status === 'pending') {
            $review->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
        } else {
            $review->update([
                'pending_rating' => $request->rating,
                'pending_comment' => $request->comment,
                'has_pending_edit' => true,
            ]);
        }

        return redirect()->route('customer.bookings.index')->with('success', 'Terimakasih atas review Anda.');
    }

    public function ensureEligible(int $fieldId, ?int $customerId): void
    {
        $hasValidBooking = Booking::where('customer_id', $customerId)->where('field_id', $fieldId)->whereIn('status', ['confirmed', 'paid', 'completed'])->exists();

        abort_unless($hasValidBooking, 403, 'Anda hanya dapat memberi review pada lapangan yang pernah Anda booking.');
    }
}
