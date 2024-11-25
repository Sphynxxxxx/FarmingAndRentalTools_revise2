<?php
session_start();
@include 'config.php';

if (!isset($_SESSION['email'])) {
    header('Location: CusProfile.php');
    exit();
}

$email = $_SESSION['email']; 

// Query to fetch customer data based on the email
$query = "SELECT * FROM customer WHERE email = '$email'";
$result = mysqli_query($conn, $query);

// Check if the query returned any result
if ($result && mysqli_num_rows($result) > 0) {
    $lender = mysqli_fetch_assoc($result);
} else {
    // Handle case where no customer was found (this should never happen if the session is valid)
    $lender = null;
    $message = 'No customer found with this email address';
}

// Handle profile update form submission
if (isset($_POST['update_profile'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $profile_image = $_FILES['profile_image']['name'];
    $profile_image_tmp_name = $_FILES['profile_image']['tmp_name'];
    $profile_image_folder = 'Cusprofile_pics/' . basename($profile_image);

    if (!empty($profile_image)) {
        // Validate the uploaded image (only JPG, PNG, JPEG allowed)
        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        $file_extension = strtolower(pathinfo($profile_image, PATHINFO_EXTENSION));

        if (in_array($file_extension, $allowed_extensions)) {
            move_uploaded_file($profile_image_tmp_name, $profile_image_folder);
            $update_image = "UPDATE customer SET profile_image = '$profile_image' WHERE email = '$email'";
            mysqli_query($conn, $update_image);
        } else {
            $message = 'Invalid image format. Only JPG, JPEG, and PNG are allowed.';
        }
    }

    // Update other profile information
    $update = "UPDATE customer SET name = '$name', address = '$address', contact_number = '$contact_number' WHERE email = '$email'";
    $update_query = mysqli_query($conn, $update);

    if ($update_query) {
        $_SESSION['message'] = 'Profile updated successfully';
    } else {
        $_SESSION['message'] = 'Failed to update profile';
    }

    header('Location: CusProfile.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css?v=1.0">
</head>
<body>

<?php
// Display message if set
if (isset($message)) {
    echo '<span class="message">' . htmlspecialchars($message) . '</span>';
} elseif (isset($_SESSION['message'])) {
    echo '<span class="message">' . htmlspecialchars($_SESSION['message']) . '</span>';
    unset($_SESSION['message']);
}
?>

<div class="container">
    <div class="profile-container">
        <!-- Profile Form -->
        <form action="CusProfile.php" method="post" enctype="multipart/form-data">
            <h3>Your Profile</h3>

            <!-- Display Profile Picture -->
            <div class="profile-picture">
                <?php if ($lender && !empty($lender['profile_image'])): ?>
                    <img src="Cusprofile_pics/<?php echo htmlspecialchars($lender['profile_image']); ?>" alt="Profile Picture" height="150">
                <?php else: ?>
                    <img src="Cusprofile_pics/default.png" alt="Default Profile Picture" height="150">
                <?php endif; ?>
            </div>

            <!-- Upload New Profile Picture -->
            <label for="profile_image">Upload New Profile Picture</label>
            <input type="file" name="profile_image" accept="image/png, image/jpeg, image/jpg" class="box">

            
            <label for="id">ID</label>
            <input type="text" name="id" value="<?php echo $lender ? htmlspecialchars($lender['id']) : ''; ?>" class="box" disabled>

            <label for="name">Name</label>
            <input type="text" name="name" value="<?php echo $lender ? htmlspecialchars($lender['name']) : ''; ?>" class="box" required>

            <label for="email">Email</label>
            <input type="email" name="email" value="<?php echo $lender ? htmlspecialchars($lender['email']) : ''; ?>" class="box" disabled>

            <label for="address">Address</label>
            <input type="text" name="address" value="<?php echo $lender ? htmlspecialchars($lender['address']) : ''; ?>" class="box" required>

            <label for="contact_number">Contact Number</label>
            <input type="text" name="contact_number" value="<?php echo $lender ? htmlspecialchars($lender['contact_number']) : ''; ?>" class="box" required>

            <!-- Submit Button -->
            <input type="submit" class="btn" name="update_profile" value="Update Profile">
        </form>

        <a href="CustomerDashboard.php" class="btn" style="margin-top: 1rem;">Home</a>
    </div>
</div>

</body>
</html>
