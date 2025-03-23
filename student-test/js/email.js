// Handle the Forgot Password form submission
document.getElementById('forgotPasswordForm').addEventListener('submit', function (e) {
    e.preventDefault();
    
    const email = document.getElementById('email').value;
    
    if (!email) {
      alert('Please enter a valid email address.');
      return;
    }
  
    alert(`Verification code sent to ${email}`);
    document.getElementById('verificationSection').style.display = 'block';
  });
  
  // Handle the Verify Email form submission
  document.getElementById('verifyEmailForm').addEventListener('submit', function (e) {
    e.preventDefault();
  
    const code = document.getElementById('code').value;
  
    if (code === '123456') { // Dummy verification code
      alert('Email verified successfully!');
    } else {
      alert('Invalid verification code. Please try again.');
    }
  });
  $(document).ready(function () {
    $('#emailForm').on('submit', function (e) {
      e.preventDefault(); // Prevent form submission

      const email = $('#email').val().trim();
      const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

      // Validate email format
      if (email === '' || !emailRegex.test(email)) {
        $('#error-message').text('Invalid email address').show();
      } else {
        $('#error-message').hide();
        alert('Email is valid!');
      }
    });
  });  