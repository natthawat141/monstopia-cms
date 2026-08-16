<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(): View
    {
        $companies = Company::withCount('projects')
            ->when(request('search'), fn ($query, $search) => $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('legal_name', 'like', "%{$search}%")
                    ->orWhere('province', 'like', "%{$search}%");
            }))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.companies.index', compact('companies'));
    }

    public function create(): View
    {
        return view('admin.companies.create', ['company' => new Company(['published' => true])]);
    }

    public function store(CompanyRequest $request): RedirectResponse
    {
        $company = Company::create($request->validated());

        return redirect()->route('admin.companies.show', $company)->with('success', 'สร้างข้อมูลบริษัทเรียบร้อยแล้ว');
    }

    public function show(Company $company): View
    {
        return view('admin.companies.show', ['company' => $company->load(['projects' => fn ($query) => $query->latest()])]);
    }

    public function edit(Company $company): View
    {
        return view('admin.companies.edit', compact('company'));
    }

    public function update(CompanyRequest $request, Company $company): RedirectResponse
    {
        $company->update($request->validated());

        return redirect()->route('admin.companies.show', $company)->with('success', 'อัปเดตข้อมูลบริษัทเรียบร้อยแล้ว');
    }

    public function destroy(Company $company): RedirectResponse
    {
        $company->delete();

        return redirect()->route('admin.companies.index')->with('success', 'ลบข้อมูลบริษัทเรียบร้อยแล้ว');
    }
}
