<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Auth::user()->vehicles()->orderByDesc('created_at')->get();
        return view('customer.vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('customer.vehicles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand'        => 'required|string|max:100',
            'model'        => 'required|string|max:100',
            'plate_number' => 'required|string|max:20|unique:vehicles,plate_number',
            'year'         => 'required|integer|min:1990|max:' . date('Y'),
            'type'         => 'required|in:motor,mobil,truk,bus',
            'color'        => 'nullable|string|max:50',
            'engine_number'  => 'nullable|string|max:50',
            'chassis_number' => 'nullable|string|max:50',
        ]);

        Vehicle::create([
            'user_id'        => Auth::id(),
            'brand'          => $request->brand,
            'model'          => $request->model,
            'plate_number'   => strtoupper($request->plate_number),
            'year'           => $request->year,
            'type'           => $request->type,
            'color'          => $request->color,
            'engine_number'  => $request->engine_number,
            'chassis_number' => $request->chassis_number,
        ]);

        return redirect()->route('customer.vehicles.index')
            ->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function edit(Vehicle $vehicle)
    {
        abort_if($vehicle->user_id !== Auth::id(), 403);
        return view('customer.vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        abort_if($vehicle->user_id !== Auth::id(), 403);

        $request->validate([
            'brand'        => 'required|string|max:100',
            'model'        => 'required|string|max:100',
            'plate_number' => 'required|string|max:20|unique:vehicles,plate_number,' . $vehicle->id,
            'year'         => 'required|integer|min:1990|max:' . date('Y'),
            'type'         => 'required|in:motor,mobil,truk,bus',
            'color'        => 'nullable|string|max:50',
        ]);

        $vehicle->update($request->only('brand','model','plate_number','year','type','color','engine_number','chassis_number'));

        return redirect()->route('customer.vehicles.index')
            ->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    public function destroy(Vehicle $vehicle)
    {
        abort_if($vehicle->user_id !== Auth::id(), 403);
        $vehicle->delete();
        return back()->with('success', 'Kendaraan berhasil dihapus.');
    }
}
