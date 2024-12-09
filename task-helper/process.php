<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inputText = $_POST['inputText'];
    $apiKey = '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';

    $data = [
        "model" => "gpt-3.5-turbo",
        "messages" => [
            ["role" => "system", "content" => "You are a helpful assistant."],
            ["role" => "user", "content" => "As a helpful assistant please help me with this: \"$inputText\""]

        ],
        "max_tokens" => 100,
        "temperature" => 0.7,
        'presence_penalty' => 0.6,
        'stream' => false,
        "top_p" => 1,
        "frequency_penalty" => 0,
    ];

    $options = [
        'http' => [
            'header' => "Content-type: application/json\r\nAuthorization: Bearer $apiKey\r\n",
            'method' => 'POST',
            'content' => json_encode($data),
        ],
    ];

    $context = stream_context_create($options);
    $result = @file_get_contents('https://api.openai.com/v1/chat/completions', false, $context);

    if ($result === FALSE) {
        // connection errors
        $error = error_get_last();
        echo json_encode(["error" => "Error processing request", "details" => $error ? $error['message'] : "Unknown error"]);
        exit;
    }

    $response = json_decode($result, true);

    if (!$response || !isset($response['choices'][0]['message']['content'])) {
        echo json_encode(["error" => "Invalid API response", "response" => $response]);
        exit;
    }

    // corrected text
    $correctedText = $response['choices'][0]['message']['content'];

    // Remove any occurrence of "The corrected sentence is: " from the beginning
    $correctedText = preg_replace('/^The corrected sentence is: /', '', $correctedText);

    // Apply bold formatting
    $correctedText = preg_replace_callback(
        '/\*\*(.*?)\*\*/',
        function ($matches) {
            return '<strong>' . $matches[1] . '</strong>';
        },
        $correctedText
    );

    echo json_encode(["correctedText" => $correctedText]);
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>