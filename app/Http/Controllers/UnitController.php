<?php
namespace App\Http\Controllers;

use App\Models\Unit;
use Auth;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Unit::select('name', 'pcs', 'id')->get();
        return view('unit.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $action = "Tambah";
        return view('unit.form', compact('action'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'pcs'  => 'required',
        ], [
            'name.required' => 'Nama sekarang wajib diisi.',
            'pcs.required'  => 'PCS wajib diisi.',
        ]);

        $item       = new Unit;
        $item->name = $request->name;
        $item->pcs  = $request->pcs;
        $item->user = Auth::user()->id;
        $item->save();

        return redirect()->route('dashboard.unit.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit)
    {
        $items  = $unit;
        $action = "Edit";
        return view('unit.form', compact('items', 'action'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'name' => 'required',
            'pcs'  => 'required',
        ], [
            'name.required' => 'Nama sekarang wajib diisi.',
            'pcs.required'  => 'PCS wajib diisi.',
        ]);

        $unit->name = $request->name;
        $unit->pcs  = $request->pcs;
        $unit->save();

        return redirect()->route('dashboard.unit.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('dashboard.unit.index');
    }
}
