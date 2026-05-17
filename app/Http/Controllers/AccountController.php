<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::orderBy('name')->paginate(10);
        return view('accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('accounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:accounts,name'],
            'type' => ['nullable', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $validated['is_active'] = $request->has('is_active');
        Account::create($validated);
        return redirect()->route('accounts.index')->with('success', 'Account created successfully.');
    }

    public function edit(Account $account)
    {
        return view('accounts.edit', compact('account'));
    }

    public function update(Request $request, Account $account)
    {
        if (in_array(trim($account->name), ['Accounts Payable', 'Accounts Receivable'])) {
            return redirect()->back()->with('error', 'Action Denied: This system master account is locked.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:accounts,name,' . $account->id],
            'type' => ['nullable', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $validated['is_active'] = $request->has('is_active');
        $account->update($validated);
        return redirect()->route('accounts.index')->with('success', 'Account updated successfully.');
    }

    public function destroy(Account $account)
    {
        if (in_array(trim($account->name), ['Accounts Payable', 'Accounts Receivable'])) {
            return redirect()->back()->with('error', 'Action Denied: This system master account is locked.');
        }

        $account->delete();
        return redirect()->route('accounts.index')->with('success', 'Account deleted successfully.');
    }
}

