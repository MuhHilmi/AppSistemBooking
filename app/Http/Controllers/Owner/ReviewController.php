<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request) {
        $ownerId = auth()->id();

        $query = Review::with(['customer', 'field.venue'])->whereHas('field.venue', function ($q) use ($ownerId) {
            $q->where('owner_id', $ownerId);
        });

        $status = $request->get('status', 'all');

        if ($status === 'pending') {
            $query->where('status', 'pending');
        } elseif ($status === 'pending_edit') {
            $query->where('has_pending_edit', true);
        } elseif ($status === 'approved') {
            $query->where('status', 'approved')->where('has_pending_edit', false);
        }

        $reviews = $query->orderByDesc('updated_at')->paginate(5)->withQueryString();

        $summary = [
            'pending' => Review::whereHas('field.venue', fn ($q) => $q->where('owner_id', $ownerId))->where('status', 'pending')->count(),
            'pending_edit' => Review::whereHas('field.venue', fn ($q) => $q->where('owner_id', $ownerId))->where('has_pending_edit', true)->count(),
        ];

        return view('owner.reviews.index', compact('reviews', 'summary'));
    }

    public function approve(Review $review) {
        abort_unless($review->field->venue->owner_id === auth()->id(), 403);

        if ($review->has_peding_edit) {
            $review->update([
                'rating' => $review->pending_rating,
                'comment' => $review->pending_comment,
                'pending_rating' => null,
                'pending_comment' => null,
                'has_pending_edit' => false,
                'status' => 'approved',
            ]);

            return back()->with('success', 'Perubahan review berhasil disetujui.');
        }

        $review->update(['status' => 'approved']);

        return back()->with('success', 'Review berhasil disetujui dan sekarang tampil ke publik.');
    }

    public function reject(Review $review) {
        abort_unless($review->field->venue->owner_id === auth()->id(), 403);

        if ($review->has_peding_edit) {
            $review->update([
                'pending_rating' => null,
                'pending_comment' => null,
                'has_pending_edit' => false,
            ]);

            return back()->with('success', 'Perubahan review ditolak. Review lama tetap tampil.');
        }

        $review->delete();

        return back()->with('success', 'Review ditolak dan dihapus.');
    }
}
