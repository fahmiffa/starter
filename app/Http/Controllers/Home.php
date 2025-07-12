<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Head;
use App\Models\Items;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class Home extends Controller
{

    public function index()
    {
        $users = [
            ['id' => 1, 'name' => 'Alice', 'email' => 'alice@example.com', 'age' => 30],
            ['id' => 2, 'name' => 'Bob', 'email' => 'bob@example.com', 'age' => 25],
            ['id' => 3, 'name' => 'Charlie', 'email' => 'charlie@example.com', 'age' => 35],
            ['id' => 4, 'name' => 'David', 'email' => 'david@example.com', 'age' => 28],
            ['id' => 5, 'name' => 'Eve', 'email' => 'eve@example.com', 'age' => 22],
            ['id' => 6, 'name' => 'Frank', 'email' => 'frank@example.com', 'age' => 29],
            ['id' => 7, 'name' => 'Grace', 'email' => 'grace@example.com', 'age' => 27],
            ['id' => 8, 'name' => 'Heidi', 'email' => 'heidi@example.com', 'age' => 33],
        ];
        return view('home', compact('users'));
    }

    public function setting()
    {

        return view('setting');
    }

    public function pass(Request $request)
    {

        $request->validate([
            'current_password'      => 'required',
            'new_password'          => 'required|min:8|same:password_confirmation',
            'password_confirmation' => 'required',
        ], [
            'current_password.required'      => 'Password sekarang wajib diisi.',
            'new_password.required'          => 'Password baru wajib diisi.',
            'new_password.min'               => 'Password baru minimal 8 karakter.',
            'new_password.same'              => 'Konfirmasi password tidak cocok.',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
        ]);

        if (! Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Password Sekarang salah']);
        }

        Auth::user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    public function trans()
    {
        $products = Items::get();
        return view('trans', compact('products'));
    }

    public function riwayat()
    {
        $products = Items::select('id', 'name', 'price', 'img')->get();
        return view('trans', compact('products'));
    }

    public function transPost(Request $request)
    {

        $da            = $request->input();
        $tot           = 0;
        $head          = new Head;
        $head->user    = Auth::user()->id;
        $head->note    = $request->note;
        $head->tanggal = date('Y-m-d');
        $head->save();

        foreach ($da as $item) {
            $tot += $item['qty'] * $item['price'];

            $cart        = new Cart;
            $cart->item  = $item['id'];
            $cart->count = $item['qty'];
            $cart->head  = $head->id;
            $cart->save();
        }

        $head->nominal = $tot;
        $head->save();

        return response()->json($da, 200);
    }
}
