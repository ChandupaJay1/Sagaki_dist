<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Models\Category;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::all();
        return view('vendors.index', compact('vendors'));
    }

    public function create()
    {
        $terms = \App\Models\PaymentTerm::where('is_active', true)->orderBy('days')->get();
        $accounts = \App\Models\Account::where('is_active', true)->orderBy('name')->get();
        $supplierCategories = \App\Models\SupplierCategory::where('is_active', true)->orderBy('name')->get();
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('vendors.create', compact('terms', 'accounts', 'supplierCategories', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:vendors',
            'code' => 'nullable|string|max:50',
            'company_name' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'main_office_no' => 'nullable|string|max:20',
            'main_office_no_2' => 'nullable|string|max:20',
            'mobile_no' => 'nullable|string|max:20',
            'fax' => 'nullable|string|max:50',
            'cc_email' => 'nullable|string|email|max:255',
            'website' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'delivery_address' => 'nullable|string|max:500',
            'currency' => 'nullable|string|max:10',
            'account_payables' => 'nullable|string|max:255',
            'terms' => 'nullable|string|max:100',
            'vat_no' => 'nullable|string|max:50',
            'svat_no' => 'nullable|string|max:50',
            'credit_limit' => 'nullable|numeric|min:0',
            'contact_person_1' => 'nullable|string|max:255',
            'contact_person_2' => 'nullable|string|max:255',
            'contact_person_3' => 'nullable|string|max:255',
            'print_name_on_cheque' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'bank_branch' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'name' => 'nullable|string|max:255',
        ]);

        $name = $request->name ?? $request->company_name ?? 'Unknown Vendor';

        Vendor::create([
            'name' => $name,
            'email' => $request->email,
            'code' => $request->code,
            'company_name' => $request->company_name,
            'category' => $request->category,
            'main_office_no' => $request->main_office_no,
            'main_office_no_2' => $request->main_office_no_2,
            'mobile_no' => $request->mobile_no,
            'phone' => $request->mobile_no,
            'fax' => $request->fax,
            'cc_email' => $request->cc_email,
            'website' => $request->website,
            'address' => $request->address,
            'delivery_address' => $request->delivery_address,
            'currency' => $request->currency,
            'account_payables' => $request->account_payables,
            'terms' => $request->terms,
            'vat_no' => $request->vat_no,
            'svat_no' => $request->svat_no,
            'credit_limit' => $request->credit_limit ?? 0,
            'contact_person_1' => $request->contact_person_1,
            'contact_person_2' => $request->contact_person_2,
            'contact_person_3' => $request->contact_person_3,
            'print_name_on_cheque' => $request->print_name_on_cheque,
            'bank_name' => $request->bank_name,
            'bank_branch' => $request->bank_branch,
            'bank_account_number' => $request->bank_account_number,
            'password' => null,
        ]);

        return redirect()->route('vendors.index')->with('success', 'Vendor registered successfully.');
    }

    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);
        $terms = \App\Models\PaymentTerm::where('is_active', true)->orderBy('days')->get();
        $accounts = \App\Models\Account::where('is_active', true)->orderBy('name')->get();
        $supplierCategories = \App\Models\SupplierCategory::where('is_active', true)->orderBy('name')->get();
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('vendors.edit', compact('vendor', 'terms', 'accounts', 'supplierCategories', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);

        $request->validate([
            'email' => 'required|string|email|max:255|unique:vendors,email,' . $id,
            'code' => 'nullable|string|max:50',
            'company_name' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'main_office_no' => 'nullable|string|max:20',
            'main_office_no_2' => 'nullable|string|max:20',
            'mobile_no' => 'nullable|string|max:20',
            'fax' => 'nullable|string|max:50',
            'cc_email' => 'nullable|string|email|max:255',
            'website' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'delivery_address' => 'nullable|string|max:500',
            'currency' => 'nullable|string|max:10',
            'account_payables' => 'nullable|string|max:255',
            'terms' => 'nullable|string|max:100',
            'vat_no' => 'nullable|string|max:50',
            'svat_no' => 'nullable|string|max:50',
            'credit_limit' => 'nullable|numeric|min:0',
            'contact_person_1' => 'nullable|string|max:255',
            'contact_person_2' => 'nullable|string|max:255',
            'contact_person_3' => 'nullable|string|max:255',
            'print_name_on_cheque' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'bank_branch' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'name' => 'nullable|string|max:255',
        ]);

        $name = $request->name ?? $request->company_name ?? 'Unknown Vendor';

        $vendor->update(array_merge($request->all(), ['name' => $name]));

        return redirect()->route('vendors.index')->with('success', 'Vendor updated successfully.');
    }

    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();

        return redirect()->route('vendors.index')->with('success', 'Vendor deleted successfully.');
    }
}
