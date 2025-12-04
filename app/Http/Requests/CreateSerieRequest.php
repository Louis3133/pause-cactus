<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSerieRequest extends FormRequest
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
            'title' => ['required', 'string', 'min:8'],
            'slug' => ['required', 'string', Rule::unique('series')->ignore($this->route('serie'))],
            'description' => ['required'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['exists:categories,id'],
        ];
    }

    protected function prepareForValidation(){
        \Log::info('prepareForValidation appelé', [
            'title' => $this->input('title'),
            'slug_avant' => $this->input('slug')
        ]);

        $this->merge([
            'slug' => $this->input('slug') ?: \Str::slug($this->input('title')),
        ]);

        \Log::info('Slug généré', ['slug_apres' => $this->input('slug')]);
    }
}
