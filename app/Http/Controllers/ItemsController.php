<?php
namespace App\Http\Controllers;

use App\Models\Items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Items::select('name', 'pcs', 'id')->get();
        return view('items.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $action = "Tambah";
        return view('items.form', compact('action'));
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

        $file = $request->file('cropped_image')->store('public/images');

        $item       = new Items;
        $item->name = $request->name;
        $item->pcs  = $request->pcs;
        $item->save();

        return redirect()->route('dashboard.items.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Items $items)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Items $items, $id)
    {
        $items  = Items::findOrFail($id);
        $action = "Edit";
        return view('items.form', compact('items', 'action'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Items $item)
    {
        $request->validate([
            'name' => 'required',
            'pcs'  => 'required',
        ], [
            'name.required' => 'Nama sekarang wajib diisi.',
            'pcs.required'  => 'PCS wajib diisi.',
        ]);

        $item->name = $request->name;
        $item->pcs  = $request->pcs;
        $item->save();

        return redirect()->route('dashboard.items.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Items $item)
    {
        $item->delete();
        return redirect()->route('dashboard.items.index');
    }
}
