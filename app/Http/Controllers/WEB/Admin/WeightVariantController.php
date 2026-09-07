<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductWeightVariant;
use App\Models\WeightVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class WeightVariantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $variants = WeightVariant::ordered()->get();

        return view('admin.inventory.weight_variants', compact('variants'));
    }

    public function store(Request $request)
    {
        try {
            $variant = $this->createVariant($request);
        } catch (ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => collect($e->errors())->flatten()->first(),
                    'errors' => $e->errors(),
                ], 422);
            }

            throw $e;
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => trans('admin_validation.Created Successfully'),
                'variant' => [
                    'id' => $variant->id,
                    'name' => $variant->name,
                    'code' => $variant->code,
                    'weight_in_kg' => (float) $variant->weight_in_kg,
                    'weight_in_gram' => (int) $variant->weight_in_gram,
                    'status' => (int) $variant->status,
                    'sort_order' => (int) $variant->sort_order,
                ],
            ]);
        }

        $notification = ['messege' => trans('admin_validation.Created Successfully'), 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update(Request $request, $id)
    {
        $variant = WeightVariant::findOrFail($id);
        $data = $this->validatedData($request, $variant->id);
        $grams = (int) $data['weight_in_gram'];

        $variant->update([
            'name' => $data['name'],
            'code' => $data['code'] ?: $variant->code,
            'weight_in_gram' => $grams,
            'weight_in_kg' => round($grams / 1000, 3),
            'status' => (int) $data['status'],
            'sort_order' => (int) ($data['sort_order'] ?? $variant->sort_order),
        ]);

        $notification = ['messege' => trans('admin_validation.Update Successfully'), 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function destroy($id)
    {
        $variant = WeightVariant::findOrFail($id);
        if (ProductWeightVariant::where('weight_variant_id', $variant->id)->exists()) {
            $notification = ['messege' => 'Cannot delete. This weight variant is assigned to products.', 'alert-type' => 'error'];

            return redirect()->back()->with($notification);
        }

        $variant->delete();
        $notification = ['messege' => trans('admin_validation.Delete Successfully'), 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function changeStatus($id)
    {
        $variant = WeightVariant::findOrFail($id);
        $variant->status = $variant->status == 1 ? 0 : 1;
        $variant->save();

        return response()->json(['success' => true, 'status' => $variant->status]);
    }

    protected function createVariant(Request $request): WeightVariant
    {
        $data = $this->validatedData($request);
        $grams = (int) $data['weight_in_gram'];
        $code = $this->uniqueCode($data['code'] ?? null, $data['name']);

        return WeightVariant::create([
            'name' => $data['name'],
            'code' => $code,
            'weight_in_gram' => $grams,
            'weight_in_kg' => round($grams / 1000, 3),
            'status' => (int) ($data['status'] ?? 1),
            'sort_order' => (int) ($data['sort_order'] ?? ((int) WeightVariant::max('sort_order') + 1)),
        ]);
    }

    protected function validatedData(Request $request, ?int $ignoreId = null): array
    {
        $uniqueCode = 'nullable|string|max:50|unique:weight_variants,code';
        if ($ignoreId) {
            $uniqueCode .= ','.$ignoreId;
        }

        return $request->validate([
            'name' => 'required|string|max:100',
            'code' => $uniqueCode,
            'weight_in_gram' => 'required|integer|min:1',
            'status' => 'nullable|in:0,1',
            'sort_order' => 'nullable|integer|min:0',
        ], [
            'name.required' => 'Weight variant name is required (example: 100g, 2kg).',
            'weight_in_gram.required' => 'Weight in gram is required.',
            'weight_in_gram.min' => 'Weight in gram must be at least 1.',
            'code.unique' => 'This code already exists. Use another code.',
        ]);
    }

    protected function uniqueCode(?string $code, string $name): string
    {
        $base = $code ?: Str::slug($name);
        if ($base === '') {
            $base = 'wv-'.time();
        }

        $candidate = $base;
        $i = 1;
        while (WeightVariant::where('code', $candidate)->exists()) {
            $candidate = $base.'-'.$i;
            $i++;
        }

        return $candidate;
    }
}
