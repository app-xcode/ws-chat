<?php
include 'X.App/X.Core/Contants.php';
include 'X.App/X.Core/Fungsi.php';
$aksi = $_GET['aksi'];
$chat_key = $_GET['chat_key'];

header('Content-Type: application/json');
$conn = DB(); // Asumsikan DB() mengembalikan koneksi database mysqli

$result = [];
if ($aksi === 'simpan_pesan') {
    $sender_type = $_POST['sender_type'];
    $sender_id = $_POST['sender_id'];
    $message = $_POST['message'];
    $image = $_POST['image'] ?? null;

    $insert = $conn->query("INSERT INTO chat (chat_key, sender_type, sender_id, message, image) VALUES ('$chat_key', '$sender_type', '$sender_id', '$message', '$image')");
    if ($insert) {
        echo json_encode(['status' => 'ok', 'id' => $conn->insert_id]);
    } else {
        echo json_encode(['status' => 'failed']);
    }
} elseif ($aksi === 'ambil_chat') {
    $last = @$_GET['last_id'];
    $last = !empty($last) ? intval($last) : 0; 
    $get = $conn->query("SELECT * FROM chat WHERE chat_key = '$chat_key' AND id > '$last' ORDER BY created_at ASC");
    while ($row = $get->fetch_assoc()) {
        $result[] = $row;
    }
    echo json_encode($result);
} elseif ($aksi === 'hapus_chat') {
    $id = !empty(@$_GET['chat_id'])? "AND id=".intval($_GET['chat_id']) : '';
    $delete = $conn->query("DELETE FROM chat WHERE chat_key = '$chat_key' $id");
    echo json_encode(['status' => $delete ? 'deleted' : 'failed']);
} else {
    echo json_encode(['error' => 'aksi tidak valid']);
}
