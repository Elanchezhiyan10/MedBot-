<?php
if(isset($_POST['message'])){
    $message = $_POST['message'];

    // Full path to Python executable
    $python = '"C:/Users/elanc/AppData/Local/Programs/Python/Python311/python.exe"';

    // Full path to Python script (use quotes and forward slashes)
    $script = '"S:/MEDBOT PROJECT/chatbotproject/medbot_chat_back.py"';

    // Build command safely
    $command = "$python $script " . escapeshellarg($message) . " 2>&1";

    $output = shell_exec($command);

    echo $output ?: "⚠️ Python command failed!";
}
?>
