<?php
session_start();

require_once "includes/connection.php";

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
    // Retrieve the email from the session
    $userEmail = $_SESSION['username'];

    // Fetch the user's name from the database
    $query = "SELECT * FROM registered_users WHERE email='$userEmail'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $userData = mysqli_fetch_assoc($result);
            $userName = $userData['name'];

            // Fetch user's VIP bookings
            $vipBookingQuery = "SELECT * FROM table_booking_vip WHERE email='$userEmail' ORDER BY date DESC";
            $vipBookingResult = mysqli_query($conn, $vipBookingQuery);

            if ($vipBookingResult) {
?>
                <?php require "includes/header.php"; ?>
                <style>
                    /* Add your styles for displaying the VIP bookings */
                    .booking-details {
                        margin-bottom: 20px;
                        border: 1px solid #ccc;
                        padding: 10px;
                        border-radius: 5px;
                    }

                    .button {
                        background-image: linear-gradient(to right, #0d5215, green);
                        color: white;
                        max-width: 600px;
                        font-size: var(--fs-7);
                        text-transform: uppercase;
                        padding: 20px 30px;
                        text-align: center;
                        border-radius: 7px;
                    }
                </style>

                <section class="contact-section" id="home">
                    <div class="contact-container">
                        <div class="contact-content">
                            <h2>Your VIP Table Bookings</h2>
                            <?php
                            while ($vipBookingData = mysqli_fetch_assoc($vipBookingResult)) {
                            ?>
                                <div class="booking-details">
                                    <p><b>Name:</b> <?php echo $userName; ?></p>
                                    <p><b>Email:</b> <?php echo $userEmail; ?></p>
                                    <p><b>Payment Status:</b> <span style="color: <?php echo ($vipBookingData['payment'] == 0) ? 'red' : 'green'; ?>"><?php echo ($vipBookingData['payment'] == 0) ? 'Not Paid [Your VIP table is not ready for you yet]' : 'Paid [Your VIP table is ready for you]'; ?></span></p>
                                    <p><b>Section:</b> <?php echo $vipBookingData['section']; ?></p>
                                    <p><b>Seat:</b> <?php echo $vipBookingData['seat']; ?></p>
                                    <p><b>Decor:</b> <?php echo $vipBookingData['decor']; ?></p>
                                    <p><b>Date & Time:</b> <?php echo $vipBookingData['date']; ?> | <?php echo $vipBookingData['time']; ?></p>
                                    <?php
                                    // Display 'Pay Now' button for unpaid VIP bookings
                                    if ($vipBookingData['payment'] == 0) {
                                    ?>
                                        <button class="button" onclick="payNow('<?php echo $vipBookingData['date']; ?>', '<?php echo $vipBookingData['time']; ?>')">Pay Now</button>
                                    <?php
                                    }
                                    ?>
                                </div>
                            <?php
                            }
                            } else {
                                // Handle VIP booking query error
                                echo "<script>alert('ERROR in processing your requests');</script>";
                            }
                        } else {
                            // Handle the case where the email is not found in the database
                            echo "<script>alert('Email not registered');</script>";
                            echo "<script>window.location.href='login.php';</script>";
                        }
                    } else {
                        // Handle database query error
                        echo "<script>alert('ERROR in proccessing your request');</script>";
                    }
                } else {
                    // Handle the case where the user is not logged in
                    echo "<script>alert('Not logged in !');</script>";
                    echo "<script>window.location.href='new-login.php';</script>";
                }
?>
                        </div>
                        <figure class="hero-banner">
                            <div class="home-right">
                                <img src="./assets/images/food1.png" alt="food image" class="food-img food-1" width="200" loading="lazy">
                                <img src="./assets/images/food2.png" alt="food image" class="food-img food-2" width="200" loading="lazy">
                                <img src="./assets/images/food3.png" alt="food image" class="food-img food-3" width="200" loading="lazy">
                                <img src="./assets/images/dialog-1.svg" alt="dialog" class="dialog dialog-1" width="230">
                                <img src="./assets/images/dialog-2.svg" alt="dialog" class="dialog dialog-2" width="230">
                                <img src="./assets/images/circle.svg" alt="circle shape" class="shape shape-1" width="25">
                                <img src="./assets/images/circle.svg" alt="circle shape" class="shape shape-2" width="15">
                                <img src="./assets/images/circle.svg" alt="circle shape" class="shape shape-3" width="30">
                                <img src="./assets/images/ring.svg" alt="ring shape" class="shape shape-4" width="60">
                                <img src="./assets/images/ring.svg" alt="ring shape" class="shape shape-5" width="40">

                            </div>
                        </figure>
                    </div>
                </section>

                <?php require "includes/footer.php"; ?>
                <script>
                    function payNow(date, time) {
                        // Implement the logic for processing VIP payment or redirect to VIP payment page
                        alert('Redirect to VIP payment page for Date: ' + date + ', Time: ' + time);
                    }
                </script>
<?php
    $conn->close();
?>
