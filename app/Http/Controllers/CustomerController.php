<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index() { return Customer::latest('id')->paginate(50); }
    public function store(Request $r) { return Customer::create($r->only('name','email','phone','address')); }
    public function show(Customer $customer) { return $customer; }
    public function update(Request $r, Customer $customer) { $customer->update($r->only('name','email','phone','address')); return $customer; }
    public function destroy(Customer $customer) { $customer->delete(); return response()->noContent(); }
}
