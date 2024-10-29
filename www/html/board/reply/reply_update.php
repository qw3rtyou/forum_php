<?php
$data = json_decode(file_get_contents("php://input"), true);

if ($data && isset($data['replyId']) && isset($data['editedContent'])) {
    require_once('../../../config/login_config.php');
    require_once('../../../config/input_config.php');
    $replyId = sqli_checker($conn, $data['replyId']);;
    $editedContent = sqli_checker($conn, $data['editedContent']);
    
    $stmt = $conn->prepare("UPDATE reply SET content = ? WHERE idx = ?");
    $stmt->bind_param("si", $editedContent, $replyId);
    $stmt->execute();
    $stmt->close();

    $response = array('success' => true);
    echo json_encode($response);
} else {
    // If the request data is invalid or missing, send an error response.
    $response = array('success' => false);
    echo json_encode($response);
}
?>

