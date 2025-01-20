<?php
session_start(); 
$messageId = $_POST['msg_id'];
$outgoing_id = $_SESSION['unique_id'];
$sql = "DELETE FROM messages WHERE msg_id = ? AND outgoing_msg_id = ?"; 
$stmt = $db->prepare($sql);
$stmt->bind_param("ii", $messageId, $outgoing_id);

if ($stmt->execute()) {
    echo "OK";
} else {

    http_response_code(500);
    echo "Ошибка при удалении сообщения: " . $stmt->error;
}

$stmt->close();
$db->close();
?>
