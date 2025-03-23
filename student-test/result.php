<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Results Management</title>
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/result.css">
</head>
<body>
    <h1>Student Results Management</h1>

    <!-- Student Results Section -->
    <div id="results">
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Score 1</th>
                    <th>Score 2</th>
                    <th>Score 3</th>
                    <th>Final Result</th>
                </tr>
            </thead>
            <tbody id="results-body">
                <!-- Rows will be dynamically added here -->
            </tbody>
        </table>
    </div>

    <!-- Analytical Reports Section -->
    <div id="reports">
        <button id="generateReport">Generate Analytical Report</button>
        <div id="report-output">
            <!-- Analytical report will be displayed here -->
        </div>
    </div>
    <footer class="footer">
        <div class="footer-links">
          <a href="#">Home</a>
          <a href="#">About Us</a>
          <a href="#">Contact</a>
          <a href="#">Privacy Policy</a>
          <a href="#">Terms of Service</a>
        </div>

    <script src="js/jquery-3.7.1.min.js">
    </script>
    <script src="js/script.js"></script>
</body>
</html>
