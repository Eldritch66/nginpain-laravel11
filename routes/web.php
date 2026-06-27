<?php

use App\Http\Controllers\Auth\SocialiteController;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\RoleSelect;
use App\Livewire\Pembayaran\PembayaranForm;
use App\Livewire\Properti\PropertiDetail;
use App\Livewire\Properti\PropertiList;
use App\Models\FotoProperti;
use App\Models\Properti;
use App\Models\Sewa;
use App\Models\TiketBantuan;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('beranda');
});

Route::view('/login', 'auth.login')->name('login');
Route::post('/login', function () {
    $data = request()->validate([
        'email' => 'required|email',
        'password' => 'required|min:6',
    ]);

    $user = User::where('email', $data['email'])->first();

    if (! $user || ! $user->password || ! Hash::check($data['password'], $user->password)) {
        return back()->withErrors(['email' => 'Email atau password salah'])->withInput();
    }

    Auth::login($user);

    if ($user->role === 'new') {
        return redirect('/role');
    }

    return redirect('/');
});

Route::get('/register', Register::class)->name('register');

Route::get('/auth/google/redirect', [SocialiteController::class, 'redirect']);
Route::get('/auth/google/callback', [SocialiteController::class, 'callback']);

Route::get('/role', RoleSelect::class)
    ->middleware('auth')
    ->name('role');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->middleware('auth');

Route::get('/properti', PropertiList::class)->name('properti.index');
Route::get('/properti/{id}', PropertiDetail::class)->name('properti.show');

Route::view('/tentang', 'tentang')->name('tentang');

Route::middleware('auth')->group(function () {
    Route::get('/pembayaran/{sewaId}', PembayaranForm::class)->name('pembayaran.form');

    Route::get('/account', function () {
        $user = Auth::user();
        if ($user->role === 'pemilik') {
            return redirect('/account/pemilik');
        }

        return view('account.index', ['user' => $user]);
    })->name('account');

    Route::get('/account/sewa', function () {
        $user = Auth::user();
        $sewaList = Sewa::where('penyewa_id', $user->id)
            ->with('properti.foto')
            ->latest()
            ->get();

        return view('account.sewa-index', ['sewaList' => $sewaList]);
    })->name('account.sewa');

    Route::get('/account/sewa/{sewaId}', function ($sewaId) {
        $user = Auth::user();
        $sewa = Sewa::with('properti.foto', 'properti.unit', 'pembayaran')
            ->where('penyewa_id', $user->id)
            ->findOrFail($sewaId);

        return view('account.sewa-detail', ['sewa' => $sewa]);
    })->name('account.sewa.detail');

    Route::get('/account/pemilik', function () {
        $user = Auth::user();
        if ($user->role !== 'pemilik') {
            return redirect('/account')->with('error', 'Anda bukan pemilik. Silakan gunakan akun pemilik.');
        }

        $totalProperti = Properti::where('pemilik_id', $user->id)->count();
        $totalAktif = Sewa::whereHas('properti', fn ($q) => $q->where('pemilik_id', $user->id))
            ->where('status_sewa', 'aktif')
            ->count();
        $totalPending = Sewa::whereHas('properti', fn ($q) => $q->where('pemilik_id', $user->id))
            ->where('status_sewa', 'pending')
            ->count();
        $propertiIds = Properti::where('pemilik_id', $user->id)->pluck('id');
        $totalKosong = $propertiIds->diff(
            Sewa::whereIn('properti_id', $propertiIds)
                ->whereIn('status_sewa', ['aktif', 'pending'])
                ->pluck('properti_id')
        )->count();
        $pendapatanAktif = Sewa::whereHas('properti', fn ($q) => $q->where('pemilik_id', $user->id))
            ->where('status_sewa', 'aktif')
            ->sum('total_harga');

        return view('account.pemilik.index', compact(
            'totalProperti', 'totalAktif', 'totalPending', 'totalKosong', 'pendapatanAktif'
        ));
    })->name('account.pemilik');

    Route::get('/account/pemilik/properti', function () {
        $user = Auth::user();
        if ($user->role !== 'pemilik') {
            return redirect('/account')->with('error', 'Anda bukan pemilik. Silakan gunakan akun pemilik.');
        }

        $propertiList = Properti::where('pemilik_id', $user->id)
            ->with('foto', 'sewa.penyewa')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('account.pemilik.properti-index', ['propertiList' => $propertiList]);
    })->name('account.pemilik.properti');

    Route::get('/account/pemilik/properti/tambah', function () {
        $user = Auth::user();
        if ($user->role !== 'pemilik') {
            return redirect('/account')->with('error', 'Anda bukan pemilik. Silakan gunakan akun pemilik.');
        }

        return view('account.pemilik.properti-tambah');
    })->name('account.pemilik.properti.tambah');

    Route::post('/account/pemilik/properti/tambah', function () {
        $user = Auth::user();
        if ($user->role !== 'pemilik') {
            return redirect('/account')->with('error', 'Anda bukan pemilik. Silakan gunakan akun pemilik.');
        }

        $data = request()->validate([
            'nama_properti' => 'required|string|max:255',
            'tipe' => 'required|in:kost,kontrakan',
            'alamat' => 'required|string',
            'harga_per_bulan' => 'required|numeric|min:0',
            'luas_bangunan' => 'nullable|numeric|min:0',
            'jumlah_kamar_tidur' => 'required|numeric|min:1',
            'jumlah_kamar_mandi' => 'required|numeric|min:1',
            'kapasitas_penghuni' => 'required|numeric|min:1',
            'lantai' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $propertiData = [
            'pemilik_id' => $user->id,
            'nama_properti' => $data['nama_properti'],
            'tipe' => $data['tipe'],
            'alamat' => $data['alamat'],
            'kota' => 'Bogor, Jawa Barat',
            'harga_per_bulan' => $data['harga_per_bulan'],
            'harga_per_dua_bulan' => $data['harga_per_bulan'] * 2,
        ];

        $properti = Properti::create($propertiData);

        Unit::create([
            'properti_id' => $properti->id,
            'luas_bangunan' => $data['luas_bangunan'],
            'jumlah_kamar_tidur' => $data['jumlah_kamar_tidur'],
            'jumlah_kamar_mandi' => $data['jumlah_kamar_mandi'],
            'kapasitas_penghuni' => $data['kapasitas_penghuni'],
            'lantai' => $data['lantai'],
            'keterangan' => $data['keterangan'],
        ]);

        if (request()->hasFile('foto')) {
            $file = request()->file('foto');
            $path = $file->store('foto_properti', 'public');
            FotoProperti::create([
                'properti_id' => $properti->id,
                'url' => '/storage/'.$path,
            ]);
        }

        return redirect('/account/pemilik/properti')->with('success', 'Properti berhasil ditambahkan');
    })->name('account.pemilik.properti.store');

    Route::get('/account/pemilik/properti/{propertiId}', function ($propertiId) {
        $user = Auth::user();
        if ($user->role !== 'pemilik') {
            return redirect('/account')->with('error', 'Anda bukan pemilik. Silakan gunakan akun pemilik.');
        }

        $properti = Properti::with('foto', 'unit')
            ->where('pemilik_id', $user->id)
            ->findOrFail($propertiId);

        $sewaHistory = Sewa::where('properti_id', $propertiId)
            ->with('penyewa')
            ->latest()
            ->get();

        return view('account.pemilik.properti-detail', compact('properti', 'sewaHistory'));
    })->name('account.pemilik.properti.detail');

    Route::get('/account/pemilik/properti/{propertiId}/edit', function ($propertiId) {
        $user = Auth::user();
        if ($user->role !== 'pemilik') {
            return redirect('/account')->with('error', 'Anda bukan pemilik. Silakan gunakan akun pemilik.');
        }

        $properti = Properti::with('foto', 'unit')
            ->where('pemilik_id', $user->id)
            ->findOrFail($propertiId);

        return view('account.pemilik.properti-edit', ['properti' => $properti]);
    })->name('account.pemilik.properti.edit');

    Route::post('/account/pemilik/properti/{propertiId}/edit', function ($propertiId) {
        $user = Auth::user();
        if ($user->role !== 'pemilik') {
            return redirect('/account')->with('error', 'Anda bukan pemilik. Silakan gunakan akun pemilik.');
        }

        $properti = Properti::where('pemilik_id', $user->id)->findOrFail($propertiId);

        $data = request()->validate([
            'nama_properti' => 'required|string|max:255',
            'tipe' => 'required|in:kost,kontrakan',
            'alamat' => 'required|string',
            'harga_per_bulan' => 'required|numeric|min:0',
            'luas_bangunan' => 'nullable|numeric|min:0',
            'jumlah_kamar_tidur' => 'required|numeric|min:1',
            'jumlah_kamar_mandi' => 'required|numeric|min:1',
            'kapasitas_penghuni' => 'required|numeric|min:1',
            'lantai' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $properti->update([
            'nama_properti' => $data['nama_properti'],
            'tipe' => $data['tipe'],
            'alamat' => $data['alamat'],
            'harga_per_bulan' => $data['harga_per_bulan'],
            'harga_per_dua_bulan' => $data['harga_per_bulan'] * 2,
        ]);

        $properti->unit()->updateOrCreate(
            ['properti_id' => $properti->id],
            [
                'luas_bangunan' => $data['luas_bangunan'],
                'jumlah_kamar_tidur' => $data['jumlah_kamar_tidur'],
                'jumlah_kamar_mandi' => $data['jumlah_kamar_mandi'],
                'kapasitas_penghuni' => $data['kapasitas_penghuni'],
                'lantai' => $data['lantai'],
                'keterangan' => $data['keterangan'],
            ]
        );

        if (request()->hasFile('foto')) {
            $file = request()->file('foto');
            $path = $file->store('foto_properti', 'public');
            $properti->foto()->delete();
            FotoProperti::create([
                'properti_id' => $properti->id,
                'url' => '/storage/'.$path,
            ]);
        }

        return redirect('/account/pemilik/properti/'.$propertiId)->with('success', 'Properti berhasil diperbarui');
    })->name('account.pemilik.properti.update');

    Route::post('/account/pemilik/properti/{propertiId}/delete', function ($propertiId) {
        $user = Auth::user();
        if ($user->role !== 'pemilik') {
            return redirect('/account')->with('error', 'Anda bukan pemilik.');
        }

        $properti = Properti::where('pemilik_id', $user->id)->findOrFail($propertiId);

        $sedangDisewa = Sewa::where('properti_id', $propertiId)
            ->whereIn('status_sewa', ['aktif', 'pending'])
            ->exists();

        if ($sedangDisewa) {
            return redirect('/account/pemilik/properti/'.$propertiId)
                ->with('error', 'Tidak dapat menghapus properti yang sedang disewa.');
        }

        $properti->delete();

        return redirect('/account/pemilik/properti')->with('success', 'Properti berhasil dihapus');
    })->name('account.pemilik.properti.delete');

    Route::post('/sewa/{sewaId}/cancel', function ($sewaId) {
        $user = Auth::user();
        $sewa = Sewa::where('penyewa_id', $user->id)->findOrFail($sewaId);

        if ($sewa->status_sewa !== 'pending') {
            return redirect('/account/sewa/'.$sewaId)->with('error', 'Sewa hanya dapat dibatalkan saat status pending.');
        }

        $sewa->update(['status_sewa' => 'dibatalkan']);

        return redirect('/account/sewa/'.$sewaId);
    })->name('sewa.cancel');

    Route::post('/sewa/{sewaId}/confirm', function ($sewaId) {
        $user = Auth::user();
        $sewa = Sewa::with('properti')->findOrFail($sewaId);

        if ($sewa->properti->pemilik_id !== $user->id) {
            return redirect('/account/pemilik')->with('error', 'Anda bukan pemilik properti ini.');
        }

        if ($sewa->status_sewa !== 'pending') {
            return redirect('/account/pemilik/properti/'.$sewa->properti_id)->with('error', 'Sewa sudah tidak dalam status pending.');
        }

        $sewa->update([
            'status_sewa' => 'aktif',
            'disetujui_pada' => now(),
        ]);

        return redirect('/account/pemilik/properti/'.$sewa->properti_id)->with('success', 'Sewa berhasil dikonfirmasi.');
    })->name('sewa.confirm');

    Route::post('/sewa/{sewaId}/reject', function ($sewaId) {
        $user = Auth::user();
        $sewa = Sewa::with('properti')->findOrFail($sewaId);

        if ($sewa->properti->pemilik_id !== $user->id) {
            return redirect('/account/pemilik')->with('error', 'Anda bukan pemilik properti ini.');
        }

        if ($sewa->status_sewa !== 'pending') {
            return redirect('/account/pemilik/properti/'.$sewa->properti_id)->with('error', 'Sewa sudah tidak dalam status pending.');
        }

        $sewa->update(['status_sewa' => 'dibatalkan']);

        return redirect('/account/pemilik/properti/'.$sewa->properti_id)->with('success', 'Sewa berhasil ditolak.');
    })->name('sewa.reject');

    Route::post('/sewa/{sewaId}/delete', function ($sewaId) {
        $user = Auth::user();
        $sewa = Sewa::where('penyewa_id', $user->id)->findOrFail($sewaId);
        if ($sewa->status_sewa === 'dibatalkan') {
            $sewa->delete();
        }

        return redirect('/account/sewa');
    })->name('sewa.delete');

    Route::get('/account/struk/{sewaId}', function ($sewaId) {
        $sewa = Sewa::with(['properti.foto', 'penyewa', 'pembayaran'])
            ->where('penyewa_id', Auth::id())
            ->findOrFail($sewaId);

        return view('account.struk', ['sewa' => $sewa]);
    })->name('account.struk');

    Route::get('/account/profile', function () {
        return view('account.profile', ['user' => Auth::user()]);
    })->name('account.profile');

    Route::post('/account/profile', function () {
        $user = Auth::user();

        if (request()->filled('current_password')) {
            $data = request()->validate([
                'current_password' => 'required|min:6',
                'new_password' => 'required|min:6|confirmed',
            ]);

            if (! Hash::check($data['current_password'], $user->password)) {
                return back()->with('error', 'Password saat ini tidak sesuai.');
            }

            $user->update(['password' => Hash::make($data['new_password'])]);

            return back()->with('success', 'Password berhasil diubah.');
        }

        $data = request()->validate([
            'name' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $updateData = ['name' => $data['name'], 'no_hp' => $data['no_hp']];

        if (request()->hasFile('avatar')) {
            $path = request()->file('avatar')->store('avatars', 'public');
            $updateData['avatar_url'] = '/storage/'.$path;
        }

        $user->update($updateData);

        return back()->with('success', 'Profil berhasil diperbarui.');
    })->name('account.profile.update');

    Route::get('/account/tiket', function () {
        $user = Auth::user();
        $tiketList = TiketBantuan::with('penjawab')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('account.tiket-index', ['tiketList' => $tiketList]);
    })->name('account.tiket');
});
