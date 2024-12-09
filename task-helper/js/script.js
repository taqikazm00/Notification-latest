$(document).ready(function () {
  // Hide output initially
  $('#output').hide()

  $('#sentence-form').on('submit', function (e) {
    e.preventDefault()
    var userInput = $('#userInput').val().trim() // Trim whitespace from input

    // Check if userInput has only English text
    if (!isValidEnglishText(userInput)) {
      $('#outputText')
        .html(
          '<div class="alert alert-danger">Invalid input. Please enter only English text.</div>'
        )
        .show()
      return
    }

    $('#loader').show()
    $('#output').hide()

    console.log('Sending request with input:', userInput)

    $.ajax({
      type: 'POST',
      url: 'process.php',
      data: { inputText: userInput },
      success: function (response) {
        $('#loader').hide()
        console.log('Response received:', response)

        try {
          var result = JSON.parse(response)
          if (result.correctedText) {
            $('#outputText').html(result.correctedText)
            $('#output').show()
          } else if (result.error) {
            $('#outputText')
              .html(
                '<div class="alert alert-danger">' +
                  result.error +
                  '<br>Details: ' +
                  JSON.stringify(result.details) +
                  '</div>'
              )
              .show()
          } else {
            $('#outputText')
              .html(
                '<div class="alert alert-danger">Unexpected response format</div>'
              )
              .show()
          }
        } catch (e) {
          console.error('Error parsing response:', e)
          $('#outputText')
            .html(
              '<div class="alert alert-danger">Error parsing response: ' +
                response +
                '</div>'
            )
            .show()
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        $('#loader').hide()
        console.error('AJAX error:', textStatus, errorThrown)
        $('#outputText')
          .html(
            '<div class="alert alert-danger">Error processing request: ' +
              textStatus +
              ' - ' +
              errorThrown +
              '</div>'
          )
          .show()
      }
    })
  })

  // check if input contains only English text
  function isValidEnglishText (input) {
    var pattern = /^[a-zA-Z,.!?'\s+\-*/=()^%<>0-9]+$/
    return pattern.test(input)
  }
})
