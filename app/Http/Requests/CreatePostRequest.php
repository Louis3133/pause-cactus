<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class CreatePostRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $rules = [
            'title' => ['required', 'string', 'min:8'],
            'slug' => [
                'required',
                'min:8',
                'regex:/^[0-9a-z\-]+$/',
                Rule::unique('posts')->ignore($this->route('post'))
            ],
            'description' => ['required', 'string'],
            'licence_id' => ['required', 'exists:licences,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['exists:categories,id'],
            'serie_id' => ['nullable', 'exists:series,id'],
        ];

        if ($this->isMethod('post')) {
            $rules['image'] = ['required', 'image', 'max:5120'];
            $rules['webtoon'] = ['required', 'image', 'max:10240'];
        } else {

            $rules['image'] = ['nullable', 'image', 'max:5120'];
            $rules['webtoon'] = ['nullable', 'image', 'max:10240'];
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => $this->input('slug')
                ? Str::slug($this->input('slug'))
                : Str::slug($this->input('title')),
        ]);
    }
}
