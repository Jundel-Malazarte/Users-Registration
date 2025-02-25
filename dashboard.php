<?php
@include 'db_connect.php';
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: sign-in.php");
    exit();
}

$user_id = $_SESSION['id'];

// Fetch user details
$sql = "SELECT fname, lname, profile_image FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
mysqli_close($conn);

$fname = htmlspecialchars($user['fname']);
$lname = htmlspecialchars($user['lname']);
$profile_image = htmlspecialchars($user['profile_image']) ?: "uploads/default.jpg";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="icon" href="./images/icon.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #333;
            padding: 10px 20px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
        }

        .navbar a:hover {
            background-color: #555;
            border-radius: 5px;
        }

        .profile-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .profile-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .book-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-top: 20px;
        }

        .book-card {
            width: 200px;
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .book-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }

        .book-card h3 {
            font-size: 16px;
            margin: 10px 0;
        }

        .book-card p {
            font-size: 14px;
            color: #555;
        }

        .add-btn {
            background-color: #28a745;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .add-btn:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>

    <div class="navbar">
        <div class="nav-links">
            <a href="dashboard.php">Home</a>
            <a href="profile.php">Profile</a>
            <a href="changepass.php">Change Password</a>
        </div>
        <div class="profile-info">
            <img src="<?php echo $profile_image; ?>" alt="Profile Image">
            <a href="profile.php"><?php echo $fname . " " . $lname; ?></a>
            <a href="logout.php">Log Out</a>
        </div>
    </div>
    <div class="container">
        <div class="form">
            <h2>Welcome, <?php echo $fname . " " . $lname; ?></h2>
        </div>
        <div class="book-card">
            <div class="book-content">
                <img src="path/to/book_image.jpg" alt="Book Image">
                <div class="book-details">
                    <h3>Book Title</h3>
                    <p><strong>ISBN:</strong> 1234567890</p>
                    <p><strong>Copyright:</strong> 2025</p>
                    <p><strong>Edition:</strong> 1st</p>
                    <p><strong>Price:</strong> $20.00</p>
                    <p><strong>Quantity:</strong> 10</p>
                    <p><strong>Total:</strong> $200.00</p>
                </div>
            </div>
        </div>

        <div class="book-card">
            <img src="./images/book1.png" alt="Harry Potter">
            <h4>Product Name</h4>
            <div>
                <span>$299</span>
                <button>+</button>
            </div>
        </div>
    </div>

    <script>
        function fetchBooks() {
            let xhr = new XMLHttpRequest();
            xhr.open("GET", "fetch_books.php", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById("book-list").innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        // Fetch books on page load and update every 5 seconds
        fetchBooks();
        setInterval(fetchBooks, 5000);
    </script>

</body>

</html>