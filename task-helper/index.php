<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Helper</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Task Helper</h1>
        <form id="sentence-form">
            <div class="form-group">
                <label for="userInput">Enter a Task:</label>
                <textarea class="form-control" id="userInput" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Check Task</button>
        </form>
        <div id="loader" style="display: none;" class="text-center">
            <img src="https://i.imgur.com/llF5iyg.gif" alt="Loading..." />
        </div>
        <div id="output" class="mt-4">
            <h2>Output:</h2>
            <div id="outputText" class="alert alert-secondary"></div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Retrieve the stored value from localStorage
            const storedDescription = localStorage.getItem('storedDescription');

            // Check if there is any stored value
            if (storedDescription) {
                // Set the value in the textarea
                document.getElementById('userInput').value = storedDescription;

                // Clear the stored value
                localStorage.removeItem('storedDescription');
            }
        });
    </script>
    <script src="js/script.js"></script>
</body>

</html>