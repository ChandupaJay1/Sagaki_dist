<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Route;
use App\Models\CustomerCategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with(['route', 'customerCategory'])->get();
        $routes = Route::where('is_active', true)->orderBy('name')->get();
        $customerCategories = CustomerCategory::orderBy('name')->get();
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('customers.index', compact('customers', 'routes', 'customerCategories', 'categories'));
    }

    public function create()
    {
        $routes = Route::where('is_active', true)->orderBy('name')->get();
        $customerCategories = CustomerCategory::orderBy('name')->get();
        $terms = \App\Models\PaymentTerm::where('is_active', true)->orderBy('days')->get();
        $accounts = \App\Models\Account::where('is_active', true)->orderBy('name')->get();
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $reps = \App\Models\User::where('is_active', 1)->orderBy('name')->get();
        return view('customers.create', compact('routes', 'customerCategories', 'terms', 'accounts', 'categories', 'reps'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:customers',
            'code' => 'nullable|string|max:50',
            'company_name' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'customer_category_id' => 'nullable|exists:customer_categories,id',
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
            // Name is handled via company_name or contact person usually, but keeping it if needed or defaulting
            'name' => 'nullable|string|max:255',
            'route_id' => 'nullable|exists:routes,id',
            'rep_id' => 'nullable|exists:users,id',
        ]);

        // If 'name' is empty, use company_name as the primary name
        $name = $request->name ?? $request->company_name ?? 'Unknown Customer';

        Customer::create([
            'route_id' => $request->route_id,
            'customer_category_id' => $request->customer_category_id,
            'name' => $name,
            'email' => $request->email,
            'code' => $request->code,
            'company_name' => $request->company_name,
            'category' => $request->category,
            'main_office_no' => $request->main_office_no,
            'main_office_no_2' => $request->main_office_no_2,
            'mobile_no' => $request->mobile_no, // Mapping mobile_no to new field
            'phone' => $request->mobile_no, // Also mapping to old phone field for backward compatibility
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
            'password' => null, // No password for CRM customers
            'rep_id' => $request->rep_id,
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer registered successfully.');
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        $routes = Route::where('is_active', true)->orderBy('name')->get();
        $customerCategories = CustomerCategory::orderBy('name')->get();
        $terms = \App\Models\PaymentTerm::where('is_active', true)->orderBy('days')->get();
        $accounts = \App\Models\Account::where('is_active', true)->orderBy('name')->get();
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $reps = \App\Models\User::where('is_active', 1)->orderBy('name')->get();
        return view('customers.edit', compact('customer', 'routes', 'customerCategories', 'terms', 'accounts', 'categories', 'reps'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        
        $request->validate([
            'email' => 'required|string|email|max:255|unique:customers,email,' . $id,
            'code' => 'nullable|string|max:50',
            'company_name' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'customer_category_id' => 'nullable|exists:customer_categories,id',
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
            'route_id' => 'nullable|exists:routes,id',
            'rep_id' => 'nullable|exists:users,id',
        ]);

        // If 'name' is empty, use company_name as the primary name
        $name = $request->name ?? $request->company_name ?? 'Unknown Customer';

        $customer->update(array_merge(
            $request->all(),
            [
                'name' => $name,
                'customer_category_id' => $request->customer_category_id,
            ]
        ));

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    /**
     * Update only the route assignment (from list page).
     */
    public function updateRoute(Request $request, Customer $customer)
    {
        $request->validate(['route_id' => 'nullable|exists:routes,id']);
        $customer->update(['route_id' => $request->route_id]);
        return redirect()->route('customers.index')->with('success', 'Route updated for customer.');
    }
}
