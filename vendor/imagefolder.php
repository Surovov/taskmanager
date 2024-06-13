<?php
function createUserImageFolder($userId) {
    $userDir = '../uploads/' . $userId;
    if (!is_dir($userDir)) {
        mkdir($userDir, 0777, true);
    }
    return $userDir;
}
?>
