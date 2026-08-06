<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function index()
    {
        try {
            $employees = Employee::orderBy('name')->paginate(15);
            
            $statistics = [
                'total' => Employee::count(),
                'active' => Employee::where('is_active', true)->count(),
                'inactive' => Employee::where('is_active', false)->count(),
            ];
            
            // Count total unique applications
            $allAplikasi = Employee::whereNotNull('aplikasi')
                ->where('aplikasi', '!=', '')
                ->pluck('aplikasi')
                ->map(function($item) {
                    return explode(', ', $item);
                })
                ->flatten()
                ->unique()
                ->filter()
                ->count();
            
            return view('employees.index', compact('employees', 'statistics', 'allAplikasi'));
            
        } catch (\Exception $e) {
            // Fallback jika error
            $employees = collect([]);
            $statistics = ['total' => 0, 'active' => 0, 'inactive' => 0];
            $allAplikasi = 0;
            
            return view('employees.index', compact('employees', 'statistics', 'allAplikasi'));
        }
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nip' => 'nullable|unique:employees',
            'position' => 'nullable|string',
            'aplikasi' => 'nullable|string',
            'email' => 'required|email|unique:employees',
            'email_korporat' => 'nullable|email',
            'phone' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $employee = Employee::create([
            'name' => $request->name,
            'nip' => $request->nip,
            'position' => $request->position,
            'aplikasi' => $request->aplikasi,
            'email' => $request->email,
            'email_korporat' => $request->email_korporat,
            'phone' => $request->phone,
            'is_active' => $request->is_active ?? true,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Pegawai berhasil ditambahkan',
            'data' => $employee
        ]);
    }
    
    public function edit(Employee $employee)
    {
        return response()->json($employee);
    }
    
    public function update(Request $request, Employee $employee)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nip' => 'nullable|unique:employees,nip,' . $employee->id,
            'position' => 'nullable|string',
            'aplikasi' => 'nullable|string',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'email_korporat' => 'nullable|email',
            'phone' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $employee->update($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Pegawai berhasil diupdate',
            'data' => $employee
        ]);
    }
    
    public function destroy(Employee $employee)
    {
        // Check if employee has any travels
        if ($employee->travels()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Pegawai masih memiliki riwayat perjalanan dinas'
            ], 400);
        }
        
        $employee->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Pegawai berhasil dihapus'
        ]);
    }
}