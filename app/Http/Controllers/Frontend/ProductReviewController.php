<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:10|max:2000',
        ]);

        $user = Auth::user();
        $product = Product::findOrFail($request->product_id);

        $hasPurchased = OrderProduct::where('product_id', $product->id)
            ->whereHas('order', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->exists();

        if (! $hasPurchased) {
            return back()
                ->with('error', 'You can only review products you have purchased.')
                ->withInput();
        }

        $alreadyReviewed = ProductReview::where('product_id', $product->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'You have already submitted a review for this product.');
        }

        $review = new ProductReview();
        $review->user_id = $user->id;
        $review->product_id = $product->id;
        $review->product_vendor_id = $product->vendor_id;
        $review->rating = (int) $request->rating;
        $review->review = $request->review;
        $review->status = 0; // pending admin approval
        $review->save();

        return back()->with('success', 'Thank you! Your review was submitted and is waiting for approval.');
    }
}
