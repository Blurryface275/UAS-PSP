<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'purchase_order_id' => 'required|exists:purchase_orders,id',

            'items' => 'required|array|min:1',

            'items.*.product_id' => 'required|exists:products,id',

            'items.*.qty' => 'required|integer|min:1',

            'items.*.price' => 'required|numeric|min:0',

        ];
    }

    public function messages(): array
    {
        return [

            'purchase_order_id.required' => 'Purchase Order wajib dipilih.',
            'purchase_order_id.exists' => 'Purchase Order tidak ditemukan.',

            'items.required' => 'Minimal harus ada satu barang.',

            'items.*.product_id.required' => 'Barang wajib dipilih.',
            'items.*.product_id.exists' => 'Barang tidak ditemukan.',

            'items.*.qty.required' => 'Qty wajib diisi.',
            'items.*.qty.integer' => 'Qty harus berupa angka.',
            'items.*.qty.min' => 'Qty minimal 1.',

            'items.*.price.required' => 'Harga wajib diisi.',
            'items.*.price.numeric' => 'Harga harus berupa angka.',
            'items.*.price.min' => 'Harga tidak boleh negatif.',
        ];
    }
}
