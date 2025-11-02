<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index() { return Vendor::latest('id')->paginate(50); }
    public function store(Request $r) { return Vendor::create($r->only('name','email','phone','address')); }
    public function show(Vendor $vendor) { return $vendor; }
    public function update(Request $r, Vendor $vendor) { $vendor->update($r->only('name','email','phone','address')); return $vendor; }
    public function destroy(Vendor $vendor) { $vendor->delete(); return response()->noContent(); }
}
