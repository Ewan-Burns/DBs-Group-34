<?php include_once("header.php")?>

<?php
// Start the session and include the database connection
// session_start();
require_once 'database_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['userID'])) {
    echo "You need to log in to view this page.";
    exit();
}

$user_id = $_SESSION['userID'];

// Check if the user is an admin
$is_admin = false;
$admin_check_query = "SELECT * FROM Admins WHERE userID = ?";
$stmt = $conn->prepare($admin_check_query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $is_admin = true;
}
$stmt->close();

if (!$is_admin) {
    echo "You do not have permission to view this page.";
    exit();
}

// Handle user deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    // Validate the userID to delete
    if (isset($_POST['user_id'])) {
        $delete_user_id = (int)$_POST['user_id'];

        // Prevent admin from deleting themselves
        if ($delete_user_id == $user_id) {
            echo "<script>alert('You cannot delete your own account.');</script>";
        } else {
            // DELETE query
            $delete_query = "DELETE FROM Users WHERE userID = ?";
            $stmt = $conn->prepare($delete_query);
            $stmt->bind_param('i', $delete_user_id);
            $stmt->execute();
            echo "<script>alert('User deleted successfully.');</script>";
            echo "<script>window.location.href = 'manage_profiles.php';</script>";
            $stmt->close();
        }
    } else {
        echo "Invalid user ID.";
    }
}

// Fetch all users
$user_query = "SELECT userID, email, firstName, lastName, dateOfBirth FROM Users";
$result = $conn->query($user_query);

$conn->close();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Manage User Profiles</title>
</head>
<body>
    <div class="container mt-5">
        <h1>Manage User Profiles</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Date of Birth</th>
                    <th>Address</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['firstName']); ?></td>
                    <td><?php echo htmlspecialchars($row['lastName']); ?></td>
                    <td><?php echo htmlspecialchars($row['dateOfBirth']); ?></td>
                    
                    <td>
                        <form method="post" action="manage_profiles.php" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            <input type="hidden" name="user_id" value="<?php echo $row['userID']; ?>">
                            <button type="submit" name="delete_user" class="btn btn-danger btn-sm">Delete User</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php $result->free(); ?>
            </tbody>
        </table>
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>

