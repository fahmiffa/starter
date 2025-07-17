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
        return view('items.index');
    }

    public function itemJson()
    {
        $items = Items::with(['size:id,name'])->get();
        return response()->json($items, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $unit   = Unit::select('name', 'id')->where('app', Auth::user()->app)->get();
        $cat    = Categori::select('name', 'id')->where('app', Auth::user()->app)->get();
        $action = "Tambah";
        return view('items.form', compact('action', 'unit', 'cat'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'price' => 'required',
        ], [
            'name.required'  => 'Nama sekarang wajib diisi.',
            'price.required' => 'Harga wajib diisi.',
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
        $item->app   = Auth::user()->app;
        $item->img   = $file;
        $item->unit  = $request->unit_id;
        $item->cat   = $request->cat_id;
        $item->save();

        return response()->json(['message' => 'success']);
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
            'name'  => 'required',
            'stok'  => 'required',
            'price' => 'required',
        ], [
            'name.required'  => 'Nama sekarang wajib diisi.',
            'stok.required'  => 'Stok wajib diisi.',
            'price.required' => 'Harga wajib diisi.',
        ]);

        $item->name  = $request->name;
        $item->price = $request->price;
        $item->stok  = $request->stok;
        $item->save();

        return response()->json(['message' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Items $item)
    {
        $item->delete();
        return response()->json(['message' => 'success']);
    }
}
