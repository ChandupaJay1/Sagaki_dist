<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'customer_category_id',
        'rep_id',
        'name',
        'email',
        'phone',
        'address',
        'delivery_address',
        'password',
        'code',
        'company_name',
        'category',
        'main_office_no',
        'main_office_no_2',
        'mobile_no',
        'fax',
        'cc_email',
        'website',
        'currency',
        'account_payables',
        'terms',
        'vat_no',
        'svat_no',
        'credit_limit',
        'contact_person_1',
        'contact_person_2',
        'contact_person_3',
        'print_name_on_cheque',
        'bank_name',
        'bank_branch',
        'bank_account_number',
    ];

    protected $hidden = [
        'password',
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function customerCategory()
    {
        return $this->belongsTo(CustomerCategory::class);
    }

    public function rep()
    {
        return $this->belongsTo(User::class, 'rep_id');
    }
}
