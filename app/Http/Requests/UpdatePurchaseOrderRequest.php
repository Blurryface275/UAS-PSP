<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'supplier_id' => 'required|exists:suppliers,id',

            'items' => 'required|array|min:1',

            'items.*.product_id' => 'required|exists:products,id',

            'items.*.qty' => 'required|integer|min:1',

            'items.*.expected_price_per_unit' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'supplier_id.required' => 'Supplier wajib dipilih.',
            'supplier_id.exists' => 'Supplier tidak ditemukan.',

            'items.required' => 'Minimal harus ada satu barang.',
            'items.array' => 'Format barang tidak valid.',

            'items.*.product_id.required' => 'Barang wajib dipilih.',
            'items.*.product_id.exists' => 'Barang tidak ditemukan.',

            'items.*.qty.required' => 'Qty wajib diisi.',
            'items.*.qty.integer' => 'Qty harus berupa angka.',
            'items.*.qty.min' => 'Qty minimal 1.',

            'items.*.expected_price_per_unit.required' => 'Harga wajib diisi.',
            'items.*.expected_price_per_unit.numeric' => 'Harga harus berupa angka.',
            'items.*.expected_price_per_unit.min' => 'Harga tidak boleh negatif.',
        ];
    }
}
