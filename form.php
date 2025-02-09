<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $secretKey = "6LfFBNIqAAAAAFMI23RGq3jTvzgxiMyq_HM9NiHI"; // reCAPTCHA Secret Key
    $responseKey = $_POST["recaptcha_response"];
    $userIP = $_SERVER["REMOTE_ADDR"];

    // Send request to Google to verify reCAPTCHA
    $url = "https://www.google.com/recaptcha/api/siteverify";
    $data = [
        'secret' => $secretKey,
        'response' => $responseKey,
        'remoteip' => $userIP
    ];

    // Use cURL to send request
    $options = [
        'http' => [
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        ]
    ];
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    $responseKeys = json_decode($response, true);

    if ($responseKeys["success"] && $responseKeys["score"] > 0.5) {
        echo "CAPTCHA verification passed. Form submitted!";
        // Process form data (e.g., save to database, send email)
    } else {
        echo "CAPTCHA verification failed. Please try again.";
    }
}
?>
