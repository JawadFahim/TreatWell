<?php
session_start();
?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
            href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css"
            rel="stylesheet"
    />
    <link rel="stylesheet" href="css/patientHomepage.css" />
    <title>TreatWell</title>
</head>

<body>

<header>

    <nav class="section__container nav__container">
        <a href="#">
            <div class="nav__logo">Treat<span>Well</span></div>
        </a>
        <ul class="nav__links">
            <li class="link"><a href="DB_of_Doctors.php">Find A Doctor</a></li>
            <li class="link"><a href="appointment_list.php">Your Appointments</a></li>
            <li class="link"><a href="medicineCat.php">Drugs</a></li>
            <li class="link"><a href="#">Health Tracker</a></li>
        </ul>
        <a href="patientProfile.php">
            <button class="btn">Profile</button>
        </a>    </nav>

    <div class="section__container header__container">
        <div class="header__content">
            <h1>Healthcare Anytime, Anywhere.</h1>
            <p>
                Welcome, where exceptional patient experiences are our priority.
                With compassionate care, state-of-the-art facilities, and a
                patient-centered approach, we're dedicated to your well-being. Trust
                us with your health and experience the difference.
            </p>
            <a href="#services" class="btn">See Services</a>
        </div>

        <?php
        $host = 'localhost';
        $db   = 'TreatWell';
        $user = 'root';
        $pass = 'root';
        $charset = 'utf8mb4';

        include "connection.php";
        global $conn;

        // Retrieve the username from the session variable
        $username = $_SESSION["username"];
        $user_id = $_SESSION["user_id"];
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, $user, $pass, $opt);

        $selectedCommonSpeciality = isset($_GET['common_speciality']) ? $_GET['common_speciality'] : '';
        $searchName = isset($_GET['name']) ? $_GET['name'] : '';

        $sql = "SELECT * FROM doctorinfo WHERE 1";
        if ($selectedCommonSpeciality !== '') {
            $sql .= " AND common_speciality = :common_speciality";
        }
        if ($searchName !== '') {
            $sql .= " AND name LIKE :name";
        }
        $stmt = $pdo->prepare($sql);
        if ($selectedCommonSpeciality !== '') {
            $stmt->bindParam(':common_speciality', $selectedCommonSpeciality);
        }
        if ($searchName !== '') {
            $searchName = "%$searchName%";
            $stmt->bindParam(':name', $searchName);
        }
        $stmt->execute();
        $doctorDetails = $stmt->fetchAll();

        $sql = "SELECT speciality from common_speciality";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $commonSpecialties = $stmt->fetchAll();

        sort($commonSpecialties);
        ?>
        <div class="header__form">
            <form action="Doctor_List.php" method="get">
                <h4>Find a Doctor</h4>
                <h3>Department</h3>
                <select id="common_speciality" name="common_speciality">
                    <option value="">Select Speciality</option>
                    <?php foreach ($commonSpecialties as $commonSpeciality): ?>
                        <option value="<?= htmlspecialchars($commonSpeciality['speciality']) ?>"><?= htmlspecialchars($commonSpeciality['speciality']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="btn form__btn" type="submit">Search by Department</button>
            </form>
        </div>
        <div class="header__form">
            <form action="Doctor_List.php" method="get">
                <h4>Find a Doctor</h4>
                <h3>Doctor</h3>
                <input type="text" id="doctorName" name="doctorName" placeholder="Enter doctor's name here">
                <button class="btn form__btn" type="submit">Search by Doctor's Name</button>
            </form>
        </div>




</header>

<section class="section__container service__container">
    <div class="service__header">
        <div class="service__header__content">
            <h2 class="section__header" id="services">Our Services</h2>
            <div class="service__grid">
                <div class="service__card">
                    <span><i class="ri-microscope-line"></i></span>
                    <h4>Available Doctors</h4>
                    <p>
                       See the list of doctors in just one click!
                    </p>
                    <a href="DB_of_Doctors.php">Find Your Doctor</a>
                </div>
                <div class="service__card">
                    <span><i class="ri-mental-health-line"></i></span>
                    <h4>Health Check</h4>
                    <p>
                        Our thorough assessments and expert evaluations help you stay
                        proactive about your health.
                    </p>
                    <a href="Health_Check.php">Health Calculator</a>
                </div>
                <div class="service__card">
                    <span><i class="ri-hospital-line"></i></span>
                    <h4>General Medicine</h4>
                    <p>
                       Check and Buy medicine
                    </p>
                    <a href="medicineCat.php">Medicine</a>
                </div>
            </div>
<!--</section>
<section class="section__container about__container">
    <div class="about__content">
        <h2 class="section__header">About Us</h2>
        <p>
            Welcome to our healthcare website, your one-stop destination for
            reliable and comprehensive health care information. We are committed
            to promoting wellness and providing valuable resources to empower you
            on your health journey.
        </p>
        <p>
            Explore our extensive collection of expertly written articles and
            guides covering a wide range of health topics. From understanding
            common medical conditions to tips for maintaining a healthy lifestyle,
            our content is designed to educate, inspire, and support you in making
            informed choices for your health.
        </p>
        <p>
            Discover practical health tips and lifestyle advice to optimize your
            physical and mental well-being. We believe that small changes can lead
            to significant improvements in your quality of life, and we're here to
            guide you on your path to a healthier and happier you.
        </p>
    </div>
    <div class="about__image">
        <img src="assets/about.jpg" alt="about" />
    </div>
</section>-->

<section class="section__container why__container">
    <div class="why__image">
        <img src="image/choose-us.jpg" alt="why choose us" />
    </div>
    <div class="why__content">
        <h2 class="section__header">Why Choose Us</h2>
        <p>
            With a steadfast commitment to your well-being, our team of highly
            trained healthcare professionals ensures that you receive nothing
            short of exceptional patient experiences.
        </p>
        <div class="why__grid">
            <span><i class="ri-hand-heart-line"></i></span>
            <div>
                <h4>Intensive Care</h4>
                <p>
                    Our Intensive Care Unit is equipped with advanced technology and
                    staffed by team of professionals
                </p>
            </div>
            <span><i class="ri-truck-line"></i></span>
            <div>
                <h4>Free Ambulance Car</h4>
                <p>
                    A compassionate initiative to prioritize your health and
                    well-being without any financial burden.
                </p>
            </div>
            <span><i class="ri-hospital-line"></i></span>
            <div>
                <h4>Medical and Surgical</h4>
                <p>
                    Our Medical and Surgical services offer advanced healthcare
                    solutions to address medical needs.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="section__container doctors__container">
    <div class="doctors__header">
        <div class="doctors__header__content">
            <h2 class="section__header">Our Special Doctors</h2>
            <p>
                We take pride in our exceptional team of doctors, each a specialist
                in their respective fields.
            </p>
        </div>


       <!-- <div class="doctors__card">
            <div class="doctors__card__image">
                <img src="image/doctor-2.jpg" alt="doctor" />
                <div class="doctors__socials">

                </div>
            </div>
            <h4>Dr. James Anderson</h4>
            <p>Neurosurgeon</p>
        </div>-->
        <?php
        // Fetch data from the database
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 3;
        $offset = ($page - 1) * $limit;


        try {
            $sql = "SELECT * FROM doctorinfo LIMIT :offset, :limit";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $doctors = $stmt->fetchAll();

            if (empty($doctors)) {
                throw new Exception('Doctor not found');
            }
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            exit;
        }

        echo '<div class="doctors__cards__container">';

        // Display the data
        foreach ($doctors as $doctor) {

            echo '<div class="doctors__card">';
            echo '<div class="doctors__card__image">';
            //echo '<img src="" alt="Doctor" />';  // Use a default image
            echo '<div class="doctors__socials">';
            // Add social media links here if needed
            echo '</div>';
            echo '</div>';
            echo '<h4>' . htmlspecialchars($doctor['name']) . '</h4>';
            echo '<p>' . htmlspecialchars($doctor['speciality']) . '</p>';
            echo '</div>';
        }

        echo '</div>'; // Close the container
        ?>
        <section class="section__container doctors__container" id="doctors"> <!-- Add id here -->
            <div class="doctors__nav">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>#doctors"><span><i class="ri-arrow-left-line"></i></span></a> <!-- Add #doctors here -->
                <?php endif; ?>
                <a href="?page=<?= $page + 1 ?>#doctors"><span><i class="ri-arrow-right-line"></i></span></a> <!-- Add #doctors here -->
            </div>
    </div>
</section>

<footer class="footer">
    <div class="section__container footer__container">
        <div class="footer__col">
            <h3>Treat<span>Well</span></h3>
            <p>
                We are honored to be a part of your healthcare journey and committed
                to delivering compassionate, personalized, and top-notch care every
            </p>
            <p>
                Trust us with your health, and let us work together to achieve the
                best possible outcomes for you and your loved ones.
            </p>
        </div>
        <div class="footer__col">
            <h4>About Us</h4>
            <p><strong>Project Group :</strong></p>
            <p>Jawad Anzum Fahim</p>
            <p>Ruponti Muin Nova</p>
            <p>Sadia Alam</p>
        </div>
        <div class="footer__col">
            <h4>Services</h4>
            <a href="DB_of_Doctors.php"><p>Doctor Search</p></a>
            <p>Medicine Search</p>
            <p>Health Calculator</p>
            <a href="appointment_list.php"><p>Appointments</p></a>
        </div>
        <div class="footer__col">
            <h4>Contact Us</h4>
            <p>
                <i class="ri-map-pin-2-fill"></i>
                <a href="https://www.google.com/maps/search/?api=1&query=Bangladesh+University+of+Professionals%2C+Mirpur+Cantonment%2C+Dhaka" target="_blank">
                    Bangladesh University of Professionals, Mirpur Cantonment, Dhaka
                </a>
            </p>

            <p><i class="ri-mail-fill"></i> <a href="mailto:treatwell@gmail.com">treatwellproject@gmail.com</a></p>
            <p><i class="ri-phone-fill"></i> <a href="facetime:01788048887">01788048887</a></p>
        </div>
    </div>
    <div class="footer__bar">
        <div class="footer__bar__content">
            <p></p>
            <div class="footer__socials">
            </div>
        </div>
    </div>
</footer>
</body>
</html>


