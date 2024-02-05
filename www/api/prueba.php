<?php
header("Content-Type: application/json");
// var_dump(http_response_code(404));
//
// // Get the new response code
// var_dump(http_response_code());
// Collect what you need in the $data variable.
$data=["nombre"=>"jose"];
$json = json_encode($data);
if ($json === false) {
    // Avoid echo of empty string (which is invalid JSON), and
    // JSONify the error message instead:
    $json = json_encode(["jsonError" => json_last_error_msg()]);
    if ($json === false) {
        // This should not happen, but we go all the way now:
        $json = '{"jsonError":"unknown"}';
    }
    // Set HTTP response status code to: 500 - Internal Server Error
    http_response_code(500);
    echo http_response_code();
    exit;
}
echo $json;
?>
