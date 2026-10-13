PRACTICAL 7 - PHP Form Processing

Topic:
PHP Form Processing with Server-Side Validation and CSV/JSON File Storage

Files:
1. index.php       -> Registration form
2. process.php     -> POST handling, validation, sanitization, CSV/JSON storage
3. records.php     -> Displays stored JSON records
4. data.csv        -> Stores submitted records in CSV format
5. data.json       -> Stores submitted records in JSON format
6. style.css       -> Simple styling

How to run in XAMPP:
1. Install/start XAMPP.
2. Start Apache.
3. Copy the practical7_php_form folder into:
   C:\xampp\htdocs\
4. Open browser and type:
   http://localhost/practical7_php_form/index.php
5. Fill the form and click Submit.
6. A success message will appear.
7. Click "View Stored Records" to see saved data.

Important:
- The form uses method="POST".
- Validation is performed in PHP on the server side.
- Email is checked using FILTER_VALIDATE_EMAIL.
- Phone is checked using a regular expression.
- Data is sanitized before output/storage.
- CSV is written using fputcsv().
- JSON is written using json_encode().
- File locking is used while writing.

Expected practical result:
Valid input -> "Registration Successful!" and data is saved.
Invalid input -> error messages are shown and data is not stored.
