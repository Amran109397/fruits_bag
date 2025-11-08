<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    /**
     * Return all customers
     */
    public function index()
    {
        try {
            $customers = Customer::select('id', 'name', 'email', 'phone', 'address')
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $customers
            ]);
        } catch (\Throwable $e) {
            Log::error('Customer index error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new customer
     */
    public function store(Request $request)
    {
        try {
            // ✅ Validation first
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:30',
                'address' => 'nullable|string|max:255',
            ]);

            // ✅ Save to DB
            $customer = Customer::create($validated);

            // ✅ Return proper JSON
            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully ✅',
                'data' => $customer
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Client-side error (422)
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'details' => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            // Server-side error (500)
            Log::error('Customer store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
