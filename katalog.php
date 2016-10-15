<?php

/**
 * Bot PHP Telegram ver Curl
 * Lebih Bersih
 * Sample Sederhana untuk Ebook Edisi 3: Membuat Bot Sendiri Menggunakan PHP.
 *
 * Dimodifikasi untuk Ebook II: Telegram Bot PHP dan Database SQL
 *
 * Dibuat oleh Hasanudin HS
 *
 * @hasanudinhs di Telegram dan Twitter
 * Email: banghasan@gmail.com
 *
 * -----------------------
 * Grup @botphp
 * Jika ada pertanyaan jangan via PM
 * langsung ke grup saja.
 * ----------------------
 * diary.php
 * Bot PHP untuk membuat diary sederhana
 * Versi 0.1
 * 10 September 2016, 8 Dzulhijjah 1437 H
 * Last Update : 10 September 2016 00:40 WIB
 *
 * Default adalah poll!
 */

/* buatlah file token.php isinya :

<?php

$token = "isiTokenBotmu";

*/
require_once 'token.php';

// masukkan bot token di sini
define('BOT_TOKEN', $token);

// versi official telegram bot
 define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

// versi 3rd party, biar bisa tanpa https / tanpa SSL.
//define('API_URL', 'https://api.pwrtelegram.xyz/bot'.BOT_TOKEN.'/');
define('myVERSI', '0.1');
define('lastUPDATE', '10 September 2016');

// ambil databasenya
require_once 'database.php';

// aktifkan ini jika ingin menampilkan debugging poll
$debug = true;

function exec_curl_request($handle)
{
    $response = curl_exec($handle);

    if ($response === false) {
        $errno = curl_errno($handle);
        $error = curl_error($handle);
        error_log("Curl returned error $errno: $error\n");
        curl_close($handle);

        return false;
    }

    $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
    curl_close($handle);

    if ($http_code >= 500) {
        // do not wat to DDOS server if something goes wrong
    sleep(10);

        return false;
    } elseif ($http_code != 200) {
        $response = json_decode($response, true);
        error_log("Request has failed with error {$response['error_code']}: {$response['description']}\n");
        if ($http_code == 401) {
            throw new Exception('Invalid access token provided');
        }

        return false;
    } else {
        $response = json_decode($response, true);
        if (isset($response['description'])) {
            error_log("Request was successfull: {$response['description']}\n");
        }
        $response = $response['result'];
    }

    return $response;
}

function apiRequest($method, $parameters = null)
{
    if (!is_string($method)) {
        error_log("Method name must be a string\n");

        return false;
    }

    if (!$parameters) {
        $parameters = [];
    } elseif (!is_array($parameters)) {
        error_log("Parameters must be an array\n");

        return false;
    }

    foreach ($parameters as $key => &$val) {
        // encoding to JSON array parameters, for example reply_markup
    if (!is_numeric($val) && !is_string($val)) {
        $val = json_encode($val);
    }
    }
    $url = API_URL.$method.'?'.http_build_query($parameters);

    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($handle, CURLOPT_TIMEOUT, 60);
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

    return exec_curl_request($handle);
}

function apiRequestJson($method, $parameters)
{
    if (!is_string($method)) {
        error_log("Method name must be a string\n");

        return false;
    }

    if (!$parameters) {
        $parameters = [];
    } elseif (!is_array($parameters)) {
        error_log("Parameters must be an array\n");

        return false;
    }

    $parameters['method'] = $method;

    $handle = curl_init(API_URL);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($handle, CURLOPT_TIMEOUT, 60);
    curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
    curl_setopt($handle, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    return exec_curl_request($handle);
}

// jebakan token, klo ga diisi akan mati
if (strlen(BOT_TOKEN) < 20) {
    die(PHP_EOL."-> -> Token BOT API nya mohon diisi dengan benar!\n");
}

function getUpdates($last_id = null)
{
    $params = [];
    if (!empty($last_id)) {
        $params = ['offset' => $last_id + 1, 'limit' => 1];
    }
  //echo print_r($params, true);
  return apiRequest('getUpdates', $params);
}

// matikan ini jika ingin bot berjalan
/*die('baca dengan teliti yak!');*/

// ----------- pantengin mulai ini
function sendMessage($idpesan, $idchat, $pesan)
{
	//add keyboard function
    var_dump($keyboard = json_encode($keyboard = [
	    'keyboard' => [
	    ["🔍Cari-Buku"],
	    ["📋Daftar", "🆘Help", "☕️Informasi"],
	    ],
	    'resize_keyboard' => true,
	    'one_time_keyboard' => false,
	    'selective' => false,
	    'force_reply' => true
	    ]), true);
	//end
    $data = [
    'chat_id'             => $idchat,
    'text'                => $pesan,
    'parse_mode'          => 'Markdown',
    'reply_to_message_id' => $idpesan,
    'reply_markup' => $keyboard,
  ];

];

    return apiRequest('sendMessage', $data);
}

function processMessage($message)
{
    global $database;
    if ($GLOBALS['debug']) {
        print_r($message);
    }

    if (isset($message['message'])) {
        $sumber = $message['message'];
        $idpesan = $sumber['message_id'];
        $idchat = $sumber['chat']['id'];

        $namamu = $sumber['from']['first_name'];
        $iduser = $sumber['from']['id'];

        if (isset($sumber['text'])) {
            $pesan = $sumber['text'];

            if (preg_match("/^\/readmore_(\d+)$/i", $pesan, $cocok)) {
                $pesan = "/readmore $cocok[1]";
            }
	/*
            if (preg_match("/^\/hapus_(\d+)$/i", $pesan, $cocok)) {
                $pesan = "/hapus $cocok[1]";
            }
	*/
     // print_r($pesan);

      $pecah = explode(' ', $pesan, 2);
            $katapertama = strtolower($pecah[0]);
            switch ($katapertama) {	             
        case '/start':
        case '!start':
          $text = '🎓 *Katalog Buku* ver.`'.myVERSI."`\n";
          $text .= "✍🏽 *Selamat Datang:*\n~~~~~~~~~~~~~~~~~~~~~~~";
          $text .= "\nBot ini digunakan untuk mencari buku dari *Katalog Buku* BOOKMART.\n";
          $text .= "Semua data yang disajikan berasal website 🌎 http://bookmart.id \n";
          $text .= "Gunakan tombol keyboard dibawah untuk memulai mengoperasikan.\n\n";
          $text .= "Semoga bermanfaat untuk semuanya,\n";
          $text .= "🙏 _Salam dan Selamat bergabung_\n~~~~~~~~~~~~~~~~~~~~~~~\n";
          break;
        
        case '/help':
        case '🆘help':
        case '!help':
          $text = "✍🏽 *Menu yang tersedia*\n~~~~~~~~~~~~~~~~~~~~~~~\n";
          $text .= "1. !daftar untuk melihat semua daftar Buku.\n";
          $text .= "2. !cari `[judul buku]` untuk mencari Buku.\n";
          $text .= "3. !waktu untuk lihat waktu sekarang.\n\n";
          $text .= "✍🏽 *Informasi lainya*\n~~~~~~~~~~~~~~~~~~~~~~~\n";
          $text .= "Untuk pemesanan dan informasinya lebih lanjut bisa menghubungi +6280000000 atau @customercare.\n";
          $text .= "~~~~~~~~~~~~~~~~~~~~~~~";
          $text .= "\n*Channel*: https://telegram.me/userchannel";
          $text .= "\n*Group*: https://telegram.me/usergroup";
          break;

        case '/informasi':
        case '☕️informasi':
        case '!informasi':
          $text = "✍🏽 *Fungsi Menu Keyboard:*\n~~~~~~~~~~~~~~~~~~~~~~~\n";
          $text .= "1. 📚 Daftar berfungsi untuk _melihat semua daftar Buku._\n";
          $text .= "2. 🔍 cari berfungsi untuk _data mencari Buku._\n";
          $text .= "3. 🆘 Help berfungsi untuk _melihat waktu sekarang._\n\n";
          $text .= "✍🏽 *Informasi lainya*\n~~~~~~~~~~~~~~~~~~~~~~~\n";
          $text .= "Untuk pemesanan dan informasinya lebih lanjut bisa menghubungi +6280000000 atau @customercare.\n";
          $text .= "~~~~~~~~~~~~~~~~~~~~~~~";
          $text .= "\n*Channel*: https://telegram.me/userchannel";
          $text .= "\n*Group*: https://telegram.me/usergroup";
          break;

        case '/waktu':
        case '!waktu':
          $text = "⌛️ Waktu Sekarang :\n";
          $text .= date('d-m-Y H:i:s');
          break;
/*
        case '/input':
          if (isset($pecah[1])) {
              $pesanproses = $pecah[1];
              $r = katalogtambah($iduser, $pesanproses);
              $text = '😘 Daftar katalog buku telah berhasil disimpan!';
          } else {
              $text = '⛔️ *ERROR:* _Pesan yang ditambahkan tidak boleh kosong!_';
              $text .= "\n\nContoh: `/input Nama: Mukidi bin Saidi`";
          }
          break;
*/
        case '/readmore':
        case '!readmore':
          if (isset($pecah[1])) {
              $pesanproses = $pecah[1];
              $text = katalogview($iduser, $pesanproses);
          } else {
              $text = '⛔️ *ERROR:* `judul buku tidak boleh kosong.`';
          }
          break;
/*
        case '/hapus':
          if (isset($pecah[1])) {
              $pesanproses = $pecah[1];
              $text = kataloghapus($iduser, $pesanproses);
          } else {
              $text = '⛔️ *ERROR:* `Judul buku tidak boleh kosong.`';
          }
          break;
*/
        case '/daftar':
        case '📋daftar':
        case '!daftar':
          $text = kataloglist($iduser);
          if ($GLOBALS['debug']) {
              print_r($text);
          }
          break;

        case '/cari-buku':
        case '🔍cari-buku':
        case '!cari-buku':
          $text = "👉🏼 Masukkan perintah `[!cari (judul buku)]`";
          break;

        case '/cari':
        case '🔍cari':
        case '!cari':
          // saya gunakan pregmatch ini salah satunya untuk mencegah SQL injection
          // hanya huruf dan angka saja yang akan diproses
          if (preg_match("/^\!cari ((\w| )+)$/i", $pesan, $cocok)) {
              $pesanproses = $cocok[1];
              $text = katalogcari($iduser, $pesanproses);
          } else {
              $text = '⛔️ *ERROR:* `kata kunci harus berupa judul saja.`';
          }
          break;

        default:
          $text = '😥 _Masukan `judul` buku yang akan dicari.._';
          break;
      }
        } else {
            $text = 'Masukkan kata kunci /cari `judul` buku untuk mencari...';
        }

        $hasil = sendMessage($idpesan, $idchat, $text);
        if ($GLOBALS['debug']) {
            // hanya nampak saat metode poll dan debug = true;
      echo 'Pesan yang dikirim: '.$text.PHP_EOL;
            print_r($hasil);
        }
    }
}

// pencetakan versi dan info waktu server, berfungsi jika test hook
echo 'Ver. '.myVERSI.' OK Start!'.PHP_EOL.date('d-m-Y H:i:s').PHP_EOL;

function printUpdates($result)
{
    foreach ($result as $obj) {
        // echo $obj['message']['text'].PHP_EOL;
    processMessage($obj);
        $last_id = $obj['update_id'];
    }

    return $last_id;
}


// AKTIFKAN INI jika menggunakan metode poll
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$last_id = null;
while (true) {
    $result = getUpdates($last_id);
    if (!empty($result)) {
        echo '+';
        $last_id = printUpdates($result);
    } else {
        echo '-';
    }

    sleep(1);
}


// AKTIFKAN INI jika menggunakan metode webhook
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
/*$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (!$update) {
  // ini jebakan jika ada yang iseng mengirim sesuatu ke hook
  // dan tidak sesuai format JSON harus ditolak!
  exit;
} else {
  // sesuai format JSON, proses pesannya
  processMessage($update);
}*/

/*

Sekian.

*/;
