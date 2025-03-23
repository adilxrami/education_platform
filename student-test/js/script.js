//this sign up page

$(document).ready(function () {
    const fieldSets = {
        student: `
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <span class="error" id="name-error"></span>

            <label for="university-number">University Number:</label>
            <input type="text" id="university-number" name="university-number" required>
            <span class="error" id="university-number-error"></span>

            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" required>
            <span class="error" id="phone-error"></span>

            <label for="department">Department:</label>
            <select id="department" name="department" required>
                <option value="">--Select department--</option>
                <option value="IS">Information System</option>
                <option value="IT">Information Technology</option>
                <option value="CS">Computer Science</option>
            </select>
            <span class="error" id="department-error"></span>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <span class="error" id="email-error"></span>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="">--Select Gender--</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
            <span class="error" id="gender-error"></span>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <span class="error" id="password-error"></span>

            <label for="confirm-password">Confirm Password:</label>
            <input type="password" id="confirm-password" name="confirm-password" placeholder="Retype your password" required>
            <span class="error" id="confirm-password-error"></span>
        `,
        professor: `
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <span class="error" id="name-error"></span>

            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" required>
            <span class="error" id="phone-error"></span>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <span class="error" id="email-error"></span>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <span class="error" id="password-error"></span>

            <label for="confirm-password">Confirm Password:</label>
            <input type="password" id="confirm-password" name="confirm-password" placeholder="Retype your password" required>
            <span class="error" id="confirm-password-error"></span>
        `,
        admin: `
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <span class="error" id="name-error"></span>

            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" required>
            <span class="error" id="phone-error"></span>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <span class="error" id="email-error"></span>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <span class="error" id="password-error"></span>

            <label for="confirm-password">Confirm Password:</label>
            <input type="password" id="confirm-password" name="confirm-password" placeholder="Retype your password" required>
            <span class="error" id="confirm-password-error"></span>
        `
    };

    $('#signup-user-type').on('change', function () {
        const userType = $(this).val();
        $('#dynamic-fields').empty();
        if (fieldSets[userType]) {
            $('#dynamic-fields').html(fieldSets[userType]);
        }
    });

    $('#signup-form').on('submit', function (e) {
        e.preventDefault();

        let isValid = true;

        // Validate fields
        $('#signup-form [required]').each(function () {
            const value = $(this).val().trim();
            const id = $(this).attr('id');
            const errorField = $(`#${id}-error`);

            if (!value) {
                isValid = false;
                errorField.text('This field is required.');
            } else {
                errorField.text('');
            }
        });

        // Validate passwords match
        const password = $('#password').val().trim();
        const confirmPassword = $('#confirm-password').val().trim();

        if (password && confirmPassword && password !== confirmPassword) {
            isValid = false;
            $('#confirm-password-error').text('Passwords do not match.');
        }

        if (isValid) {
            alert('Form submitted successfully!');
            // Proceed with form submission logic (e.g., AJAX request)
        }
    });
});
//this sign in page
$(document).ready(function () {
    const fieldSets = {
        student: `
            <label for="username">University Number:</label>
            <input type="text" id="username" name="username" placeholder="Enter your university number" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
             
            <a href="emailverify.html">Forgot your password?</a>
  </div>
        `,
        professor: `
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
         
            
            <a href="emailverify.html">Forgot your password?</a>
  </div>
            `,
        admin: `
            <label for="email">email:</label>
            <input type="email" id="email" name="email" required>
                   <span class="error" id="email-error"></span>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
        
           
            <a href="emailverify.html">Forgot your password?</a>
          </div>
            `
    };

    $('#login-user-type').on('change', function () {
        const userType = $(this).val();

        // Clear existing dynamic fields
        $('#dynamic-fields').empty();

        // Add new fields based on selected user type
        if (fieldSets[userType]) {
            $('#dynamic-fields').html(fieldSets[userType]);
        }
    });
});
//
$(document).ready(function () {
    // Toggle FAQ answers
    $('.faq-item button').on('click', function () {
        $(this).next('.faq-answer').slideToggle();
    });

    // Handle form submission
    $('.contact-form').on('submit', function (e) {
        e.preventDefault();

        const userName = $('#name').val();
        const userEmail = $('#email').val();
        const userIssue = $('#issue').val();

        // Simulate sending data to server
        alert(`Support request submitted by ${userName}.\nIssue: ${userIssue}\nWe'll respond to ${userEmail} soon!`);

        // Clear form after submission
        this.reset();
    });
});
//result management
$(document).ready(function() {
    // Example student data
    const students = [
        { name: "John Doe", score1: 85, score2: 90, score3: 88 },
        { name: "Jane Smith", score1: 78, score2: 80, score3: 72 },
        { name: "Jim Brown", score1: 92, score2: 94, score3: 89 }
    ];

    // Function to calculate final result
    function calculateFinalResult(scores) {
        return (scores.score1 + scores.score2 + scores.score3) / 3;
    }

    // Display student results
    students.forEach(function(student) {
        const finalResult = calculateFinalResult(student);
        const row = `<tr>
                        <td>${student.name}</td>
                        <td>${student.score1}</td>
                        <td>${student.score2}</td>
                        <td>${student.score3}</td>
                        <td>${finalResult.toFixed(2)}</td>
                    </tr>`;
        $('#results-body').append(row);
    });

    // Generate analytical report when button is clicked
    $('#generateReport').click(function() {
        let totalStudents = students.length;
        let highestScore = 0;
        let lowestScore = 100;
        let totalScore = 0;

        students.forEach(function(student) {
            const finalResult = calculateFinalResult(student);
            totalScore += finalResult;
            if (finalResult > highestScore) highestScore = finalResult;
            if (finalResult < lowestScore) lowestScore = finalResult;
        });

        let averageScore = totalScore / totalStudents;

        // Display report
        const report = `
            <h3>Analytical Report</h3>
            <p>Total Students: ${totalStudents}</p>
            <p>Highest Score: ${highestScore.toFixed(2)}</p>
            <p>Lowest Score: ${lowestScore.toFixed(2)}</p>
            <p>Average Score: ${averageScore.toFixed(2)}</p>
        `;
        $('#report-output').html(report);
    });
});

