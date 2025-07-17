<?php
namespace App\Http\Controllers;

use App\Models\App;
use App\Models\Cart;
use App\Models\Head;
use App\Models\Items;
use App\Models\Stok;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Home extends Controller
{

    public function app()
    {

        $item = [];
        return view('app.index', compact('item'));
    }

    public function appJson()
    {
        $app = App::with('users')->get();
        return response()->json($app, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required',
            'hp'       => 'required',
            'password' => 'required',
            'app'      => 'required',
        ], [
            'name.required'     => 'Nama wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'hp.required'       => 'Nomor HP wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'app.required'      => 'Aplikasi wajib diisi.',
        ]);

        $app       = new App;
        $app->name = $request->app;
        $app->save();

        $user           = new User;
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->nomor    = $request->hp;
        $user->role     = 1;
        $user->status   = 1;
        $user->password = bcrypt($request->password);
        $user->app      = $app->id;
        $user->save();

        return response()->json(['message' => 'success']);
    }

    public function update(Request $request, $id, $user)
    {
        $app  = App::findOrfail($id);
        $user = User::findOrfail($user);
        $request->validate([
            'name'  => 'required',
            'email' => 'required',
            'hp'    => 'required',
            'app'   => 'required',
        ], [
            'name.required'  => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'hp.required'    => 'Nomor HP wajib diisi.',
            'app.required'   => 'Aplikasi wajib diisi.',
        ]);

        $app->name = $request->app;
        $app->logo = null;
        $app->save();

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->nomor = $request->hp;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->app = $app->id;
        $user->save();

        return response()->json(['message' => 'success']);
    }

    public function destroy($id, $user)
    {
        App::where('id', $id)->delete();
        User::where('id', $user)->delete();
        return response()->json(['message' => 'success']);
    }

    public function index()
    {
        return view('home');
    }

    public function chart()
    {

        $data = DB::table('carts')
            ->selectRaw('YEAR(created_at) as tahun, MONTH(created_at) as bulan, SUM(count) as total_penjualan')
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at)')
            ->get();

        $bulanMap = [
            1 => 'Jan', 2  => 'Feb', 3  => 'Mar', 4  => 'Apr',
            5 => 'Mei', 6  => 'Jun', 7  => 'Jul', 8  => 'Aug',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];

        $dummyData = [];

        foreach ($data->pluck('tahun')->unique() as $tahun) {
            foreach ($bulanMap as $num => $namaBulan) {
                $dummyData[$tahun][$namaBulan] = 0;
            }
        }

        foreach ($data as $item) {
            $tahun                     = $item->tahun;
            $bulan                     = $bulanMap[$item->bulan];
            $dummyData[$tahun][$bulan] = (int) $item->total_penjualan;
        }

        $now   = (int) date('Y');
        $start = (int) env('APP_START');
        $year  = [];

        for ($tahun = $now; $tahun >= $start; $tahun--) {
            array_push($year, $tahun);
        }

        $da = [
            "Year" => $year,
            "data" => $dummyData,
        ];

        return response()->json($da, 200);
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
        $head     = Head::with('cart.items')->get();
        return view('riwayat', compact('products', 'head'));
    }

    public function transPost(Request $request)
    {

        $da            = $request->input();
        $tot           = 0;
        $head          = new Head;
        $head->user    = Auth::user()->id;
        $head->app     = Auth::user()->app;
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

            $stok         = new Stok;
            $stok->item   = $item['id'];
            $stok->count  = -$item['qty'];
            $stok->user   = Auth::user()->id;
            $stok->status = 0;
            $stok->app    = Auth::user()->app;
            $stok->save();
        }

        $head->nominal = $tot;
        $head->save();

        return response()->json($da, 200);
    }
}
