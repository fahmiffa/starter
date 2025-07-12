<?php
namespace App\Http\Controllers;

use App\Models\Categori;
use App\Models\Items;
use App\Models\Unit;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Items::select('name', 'img', 'unit', 'id', 'stok', 'price')->with('size', function ($q) {
            $q->select('id', 'name');
        })->get();
        return view('items.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $unit   = Unit::select('name', 'id')->where('user', Auth::user()->id)->get();
        $cat    = Categori::select('name', 'id')->where('user', Auth::user()->id)->get();
        $action = "Tambah";
        return view('items.form', compact('action', 'unit', 'cat'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required',
            'price'  => 'required',
            'cat_id' => 'required',
        ], [
            'name.required'   => 'Nama sekarang wajib diisi.',
            'price.required'  => 'Harga wajib diisi.',
            'cat_id.required' => 'Unit wajib diisi.',
        ]);

        $img  = $request->file('cropped_image');
        $file = null;

        if ($img) {
            $file = $img->store('images', 'public');

        }

        $item        = new Items;
        $item->name  = $request->name;
        $item->price = $request->price;
        $item->stok  = $request->stok;
        $item->user  = Auth::user()->id;
        $item->img   = $file;
        $item->unit  = $request->unit_id;
        $item->cat   = $request->cat_id;
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
        $unit   = Unit::select('name', 'id')->where('user', Auth::user()->id)->get();
        $cat    = Categori::select('name', 'id')->where('user', Auth::user()->id)->get();
        $action = "Edit";
        return view('items.form', compact('items', 'action', 'unit', 'cat'));
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
