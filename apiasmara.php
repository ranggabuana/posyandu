<?php
// Kredensial rahasia — simpan di luar web root jika memungkinkan
define('API_KEY', 'k3y_puw4r3jaa_2026_xK9!mQ#');      // Ganti dengan key unik
define('API_PASS', 'p@ss_53cr3t_p0syanduv_!LmN8$');    // Ganti dengan password kuat

// Header JSON
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

// Cek method harus GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Ambil header Authorization
$auth_header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';

// Format: Authorization: Basic base64(api_key:api_pass)
if (empty($auth_header) || !preg_match('/Basic\s+(.*)/i', $auth_header, $matches)) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized: Missing or invalid Authorization header']);
    exit;
}

$credentials = base64_decode($matches[1]);
if ($credentials === false) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized: Invalid base64']);
    exit;
}

if (!strpos($credentials, ':')) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized: Invalid credential format']);
    exit;
}

list($key, $pass) = explode(':', $credentials, 2);

if ($key !== API_KEY || $pass !== API_PASS) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized: Invalid API key or password']);
    exit;
}

// Jika lolos, lanjutkan ke koneksi database
$host = 'localhost';
$dbname = 'purwareja-klampok-purwareja';
$username = 'purwareja-klampok-purwareja';
$password = 'Buana1234.,.,';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "
        SELECT
            p.id,
            p.nama,
            k.no_kk,
            p.nik,
            kk.nama AS nama_kk,
            h.nama AS hubungan_keluarga,
            CASE 
                WHEN p.sex = 1 THEN 'Laki-Laki'
                WHEN p.sex = 2 THEN 'Perempuan'
                ELSE ''
            END AS kelamin,
            p.tempatlahir,
            p.tanggallahir,
            a.nama AS agama,
            pend.nama AS pendidikan,
            pek.nama AS pekerjaan,
            kawin.nama AS status_kawin,
            p.nama_ayah,
            p.nama_ibu,
            p.bpjs_ketenagakerjaan,
            p.telepon,
            g.nama AS goldar,
            k.alamat,
            c.rw,
            c.rt,
            c.dusun
        FROM tweb_penduduk p
        LEFT JOIN tweb_keluarga k ON p.id_kk = k.id
        LEFT JOIN tweb_penduduk kk ON k.nik_kepala = kk.id
        LEFT JOIN tweb_penduduk_hubungan h ON p.kk_level = h.id
        LEFT JOIN tweb_penduduk_agama a ON p.agama_id = a.id
        LEFT JOIN tweb_penduduk_pendidikan_kk pend ON p.pendidikan_kk_id = pend.id
        LEFT JOIN tweb_penduduk_pekerjaan pek ON p.pekerjaan_id = pek.id
        LEFT JOIN tweb_penduduk_kawin kawin ON p.status_kawin = kawin.id
        LEFT JOIN tweb_golongan_darah g ON p.golongan_darah_id = g.id
        LEFT JOIN tweb_wil_clusterdesa c ON k.id_cluster = c.id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}
