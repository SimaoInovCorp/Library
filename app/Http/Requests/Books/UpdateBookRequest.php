<?php

namespace App\Http\Requests\Books;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'isbn' => 'required|string|max:255|unique:books,isbn,' . $this->route('book')->id,
            'name' => 'required|string|max:255',
            'bibliography' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
            'price' => 'nullable|numeric|min:0',
            'publisher_id' => 'required|exists:publishers,id',
            'authors' => 'required|array',
            'authors.*' => 'exists:authors,id',
        ];
    }
}
