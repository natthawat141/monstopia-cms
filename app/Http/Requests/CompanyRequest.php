<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class CompanyRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() || $this->user()?->isEditor();
    }

    public function rules(): array
    {
        $company = $this->route('company');
        $companyId = is_object($company) ? $company->id : $company;

        return [
            'name' => ['required', 'string', 'max:255'],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('companies', 'slug')->ignore($companyId)],
            'registration_number' => ['nullable', 'string', 'max:100'],
            'registered_at' => ['nullable', 'date'],
            'province' => ['nullable', 'string', 'max:120'],
            'business_type' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'published' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'published' => $this->boolean('published'),
        ]);
    }
}
