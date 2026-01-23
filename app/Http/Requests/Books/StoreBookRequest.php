<?php

namespace App\Http\Requests\Books;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidTitleName;

class StoreBookRequest extends FormRequest
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
            'isbn' => 'required|string|max:255|unique:books,isbn',
            'name' => ['required', 'string', 'max:255', new ValidTitleName],
            'bibliography' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
            'price' => 'nullable|numeric|min:0',
            'publisher_id' => 'required|exists:publishers,id',
            'copies' => 'required|integer|min:0',
            'authors' => 'required|array',
            'authors.*' => 'exists:authors,id',
        ];
    }
}
