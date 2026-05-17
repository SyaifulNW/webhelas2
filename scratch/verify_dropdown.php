<?php
use App\Models\User;
use App\Models\Data;
use App\Models\Kelas;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

// Mock user
$user = new User();
$user->role = 'cs-mbc';
$user->name = 'Linda';
Auth::shouldReceive('user')->andReturn($user);
Auth::shouldReceive('check')->andReturn(true);

// Create some test classes: one in past, one in future
$pastKelas = new Kelas();
$pastKelas->id = 999;
$pastKelas->nama_kelas = 'PAST CLASS';
$pastKelas->tanggal_selesai = '2020-01-01';

$futureKelas = new Kelas();
$futureKelas->id = 888;
$futureKelas->nama_kelas = 'FUTURE CLASS';
$futureKelas->tanggal_selesai = '2030-01-01';

$noDateKelas = new Kelas();
$noDateKelas->id = 777;
$noDateKelas->nama_kelas = 'NO DATE CLASS';
$noDateKelas->tanggal_selesai = null;

$kelasCollection = collect([$pastKelas, $futureKelas, $noDateKelas]);

$item = Data::first();
$loop = (object) ['index' => 0, 'iteration' => 1];

try {
    $html = View::make('admin.database.partials.row_mbc', [
        'item' => $item,
        'loop' => $loop,
        'kelas' => $kelasCollection,
    ])->render();

    // Extract data-kelas attribute
    if (preg_match("/data-kelas='(.*?)'/", $html, $matches)) {
        $json = html_entity_decode($matches[1]);
        $data = json_decode($json, true);
        
        $names = array_column($data, 'nama');
        
        echo "Classes in data-kelas: " . implode(', ', $names) . "\n";
        
        if (in_array('PAST CLASS', $names)) {
            echo "FAIL: PAST CLASS found in dropdown data.\n";
        } else {
            echo "SUCCESS: PAST CLASS hidden from dropdown data.\n";
        }
        
        if (in_array('FUTURE CLASS', $names)) {
            echo "SUCCESS: FUTURE CLASS found in dropdown data.\n";
        } else {
            echo "FAIL: FUTURE CLASS NOT found in dropdown data.\n";
        }

        if (in_array('NO DATE CLASS', $names)) {
            echo "SUCCESS: NO DATE CLASS found in dropdown data.\n";
        } else {
            echo "FAIL: NO DATE CLASS NOT found in dropdown data.\n";
        }
    } else {
        echo "Error: Could not find data-kelas attribute in rendered HTML.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
