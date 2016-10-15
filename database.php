<?php

/*
* Ebook II: Telegram Bot PHP dan Database SQL
* oleh bang Hasan ( @hasanudinhs )
*
* Fungsi Database untuk Diary Bot Telegram
*
*/


// masukkan database framework nya
require_once 'medoo.php';

// koneksikan ke database

// ini contoh menggunakan SQLite
/*
    $database = new medoo([
        'database_type' => 'sqlite',
        'database_file' => 'diary.db',
    ]);
*/
// uncomment ini jika menggunakan mySQL atau mariaDB
// sesuaikan nama database, host, user, dan passwordnya

    $database = new medoo([
        'database_type' => 'mysql',
        'database_name' => 'namadatabae',
        'server' => 'localhost',
        'username' => 'root',
        'password' => 'password',
        'charset' => 'utf8'
    ]);

// fungsi untuk menambah data
function katalogtambah($iduser, $pesan)
{
    global $database;
    $last_id = $database->insert('katalog_buku', [
        'id'    => $iduser,
        'waktu' => date('d-m-Y H:i:s').' WIB',
        'pesan' => $pesan,
    ]);

    return $last_id;
}

// fungsi menghapus data
function kataloghapus($iduser, $idpesan)
{
    global $database;
    $database->delete('katalog_buku', [
        'AND' => [
            'id' => $iduser,
            'no' => $idpesan,
        ],
    ]);

    return '⛔️ telah dilaksanakan..';
}

// fungsi melihat daftar katalog buku
function kataloglist($iduser, $page = 0)
{
    global $database;
    $hasil = '😢 Maaf ya, Buku tidak ditemukan, coba lagi deh!';
    $datas = $database->select('katalog_buku', [ // table database
        'no',
        'id',
        'kode',
        'judul',
        'penulis',
        'isbn',
        'harga',
        'ukuran',
        'sinopsis',
        'profil_penulis',
        'kategori',
        'tahun_terbit',
        'penerbit',
        'stock',
        'photo',
    ]);
    $jml = count($datas);
    if ($jml > 0) {
        $hasil = "📖 Daftar Buku Tersedia `[$jml]`:\n";
        $n = 0;
        foreach ($datas as $data) {
            $n++;
            $hasil .= "=================================\n";
            $hasil .= "$n. *$data[judul]*\n";
            $hasil .= "=================================\n";
            $hasil .= "✍🏽 Penulis: *$data[penulis]*\n";
            $hasil .= "🔰 ISBN: *$data[isbn]*\n";
            $hasil .= "=================================\n";
            $hasil .= "`  Harga    :` *$data[harga]*\n";
            $hasil .= "`  Kategori :` *$data[kategori]*\n";
            $hasil .= "`  Th Terbit:` *$data[tahun_terbit]*\n";
            $hasil .= "    👉 /readmore\_$data[no]\n";
        }
    }

    return $hasil;
}

// fungsi melihat isi pesan data
# Ini yang perlu dirombak untuk disesuaikan dengan database
function katalogview($iduser, $idpesan)
{
    global $database;
    $hasil = "😢 Maaf ya, Buku tidak ditemukan, coba lagi deh!..";
    $datas = $database->select('katalog_buku', [
    // ganti table sesuai dengan table database
    // list table dibawah merupakan table yang akan menjadi sasaran untuk di searching (cari)
        'no',
        'id',
        'kode',
        'judul',
        'penulis',
        'isbn',
        'harga',
        'ukuran',
        'sinopsis',
        'profil_penulis',
        'kategori',
        'tahun_terbit',
        'penerbit',
        'stock',
        'photo',
    ], [
        'AND' => [
            //'id' => $iduser,
            'no' => $idpesan,
            //'photo' =>$photo,
        ],
    ]);
    // menampilkan data yang ditemukan sesuai dengan yang dicari
    $jml = count($datas);
    if ($jml > 0) {
        $data = $datas[0];
        $hasil = "📖 Data Buku:\n🗳 *KODE:$data[kode]*\n=================================";
        $hasil .= "\n📗 *Judul Buku*";
        $hasil .= "\n=================================";
        $hasil .= "\n*$data[judul]*";
        $hasil .= "\n================================="; 
        $hasil .= "\n`Penulis   :` *$data[penulis]*";
        $hasil .= "\n`ISBN      :` *$data[isbn]*";
        $hasil .= "\n`Harga     :` *$data[harga]*";
        $hasil .= "\n`Ukuran    :` *$data[ukuran]*";
        $hasil .= "\n`Stock     :` *$data[stock] Examplar*";
        $hasil .= "\n`Kategori  :` *$data[kategori]*";
        $hasil .= "\n`Th Terbit :` *$data[tahun_terbit]*";
        $hasil .= "\n`Penerbit  :` *$data[penerbit]*";
        $hasil .= "\n=================================";
        $hasil .= "\n📃 *Sinopsis*";
        $hasil .= "\n=================================";
        $hasil .= "\n$data[sinopsis]";
        $hasil .= "\n=================================";
        $hasil .= "\n👤 *Profil Penulis*";
        $hasil .= "\n=================================";
        $hasil .= "\n$data[profil_penulis]";
        $hasil .= "\n=================================";   
        $hasil .= "\n🙏 *Order Buku Hubungi:* @ptbookmart";
        $hasil .= "\n=================================";         
        $hasil .= "\n*Cover Buku*:\n";
        // untuk menampilkan cover buku di load dari url website
        $hasil .= "http://cdn.greenbox.web.id/cdn/img/bookmart/{$data['photo']}"; // hanya mengganti url depan saja.
    }

    return $hasil;
}

// fungsi mencari pesan di data
function katalogcari($iduser, $pesan)
{
    global $database;
    $hasil = '😢 *Judul tidak ditemukan*, _coba lagi deh!_..';
    $datas = $database->select('katalog_buku', [
    // list table dibawah merupakan table yang akan menjadi sasaran untuk di searching (cari)
        'no',
        'id',
        'kode',
        'judul',
        'penulis',
        'isbn',
        'harga',
        'ukuran',
        'sinopsis',
        'profil_penulis',
        'kategori',
        'tahun_terbit',
        'penerbit',
        'stock',
        'photo',
    ], [
        'judul[~]' => $pesan,
    ]);
    // menampilkan data yang ditemukan sesuai dengan yang dicari
    $jml = count($datas);
    if ($jml > 0) {
        $hasil = "🔍 Ditemukan `[$jml]` Judul Buku:\n";
        $n = 0;
        foreach ($datas as $data) {
            $n++;
            $hasil .= "=================================\n";
            $hasil .= "$n. *$data[judul]*\n";
            $hasil .= "=================================\n";
            $hasil .= "✍🏽 Penulis: *$data[penulis]*\n";
            $hasil .= "🔰 ISBN: *$data[isbn]*\n";
            $hasil .= "=================================\n";
            $hasil .= "`  Harga    :` *$data[harga]*\n";
            $hasil .= "`  Kategori :` *$data[kategori]*\n";
            $hasil .= "`  Th Terbit:` *$data[tahun_terbit]*\n";
            $hasil .= "    👉 /readmore\_$data[no]\n";
        }
    }

    return $hasil;
}
