$(document).ready(function() {
  

    
    function checkConfirmPassword() {
        var passwordInput = $('#password');
        var confirmPasswordInput = $('#confirm-password');
        var warningDiv = $('#confirm-password-warning');

        if (passwordInput.val() !== confirmPasswordInput.val()) {
            warningDiv.text('Passwords do not match!');
            warningDiv.show();
        } else {
            warningDiv.hide();
        }
    }

    // Attach the checkConfirmPassword function to the "input" event of the confirm password input
    $('#confirm-password').on('input', function() {
        checkConfirmPassword();
    });


    // Function to check if name starts with a number and show/hide warning
    function checkNameInput(input) {
        var warningDiv = $('#' + input.attr('id') + '-warning');
        var startsWithNumber = /^[0-9].*$/;

        if (startsWithNumber.test(input.val())) {
            warningDiv.text('Name can\'t start with a number!');
            warningDiv.show();
        } else {
            warningDiv.hide();
        }
    }

    // AJAX for validating first and last names
    $('#first-name, #last-name').on('input', function() {
        var input = $(this);
        console.log('Input event triggered:', input.val()); // Debugging statement
        checkNameInput(input);
    });
});
