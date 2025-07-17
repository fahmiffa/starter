<?php
namespace App\Http\Controllers;

use App\Models\Categori;
use Auth;
use Illuminate\Http\Request;

class CategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Categori::where('app', Auth::user()->app)->get();
        return view('categori.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $action = "Tambah";
        return view('categori.form', compact('action'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ], [
            'name.required' => 'Nama wajib diisi.',
        ]);

        $item       = new Categori;
        $item->name = $request->name;
        $item->app  = Auth::user()->app;
        $item->save();

        return response()->json(['message' => 'success']);

    }

    public function categoriJson()
    {
        $items = Categori::where('app', Auth::user()->app)->get();
        return response()->json($items, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Categori $categori)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categori $categori)
    {
        $items  = $categori;
        $action = "Edit";
        return view('categori.form', compact('items', 'action'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categori $categori)
    {
        $request->validate([
            'name' => 'required',
        ], [
            'name.required' => 'Nama sekarang wajib diisi.',
        ]);

        $categori->name = $request->name;

        $categori->save();

        return response()->json(['message' => 'success']);

        // return redirect()->route('dashboard.categori.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categori $categori)
    {
        $categori->delete();
        return response()->json(['message' => 'success']);
    }
}
