
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
            href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css"
            rel="stylesheet"
    />
    <link rel="stylesheet" href="index.css" />
    <title>TreatWell Homepage</title>
</head>

<body>

<header>

    <nav class="section__container nav__container">
        <div class="nav__logo">Treat<span>Well</span></div>
        <ul class="nav__links">
            <li class="link"><a href="DB_of_Doctors.php">Find A Doctor</a></li>
            <li class="link"><a href="DB_of_Doctors.php">Book Appointment</a></li>
            <li class="link"><a href="#">Drugs</a></li>
            <li class="link"><a href="#">Health Tracker</a></li>
        </ul>
        <button class="btn">Profile</button>
    </nav>

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

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, $user, $pass, $opt);

        $selectedSpecialty = isset($_GET['specialty']) ? $_GET['specialty'] : '';
        $searchName = isset($_GET['doctorName']) ? $_GET['doctorName'] : '';

        $sql = "SELECT * FROM doctor WHERE 1";
        if ($selectedSpecialty !== '') {
            $sql .= " AND specialty = :specialty";
        }
        if ($searchName !== '') {
            $sql .= " AND name LIKE :name";
        }
        $stmt = $pdo->prepare($sql);
        if ($selectedSpecialty !== '') {
            $stmt->bindParam(':specialty', $selectedSpecialty);
        }
        if ($searchName !== '') {
            $searchName = "%$searchName%";
            $stmt->bindParam(':name', $searchName);
        }
        $stmt->execute();
        $doctorDetails = $stmt->fetchAll();

        $sql = "SELECT DISTINCT specialty FROM doctor";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $specialties = $stmt->fetchAll();
        ?>
        <div class="header__form">
            <form action="Doctor_List.php" method="get">
                <h4>Find a Doctor</h4>
                <h3>Department</h3>
                <select id="department" name="department">
                    <option value="">Select Speciality</option>
                    <?php foreach ($specialties as $specialty): ?>
                        <option value="<?= htmlspecialchars($specialty['specialty']) ?>"><?= htmlspecialchars($specialty['specialty']) ?></option>
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
                        Accurate Diagnostics, Swift Results: Experience top-notch Laboratory
                        Testing at our facility.
                    </p>
                    <a href="#">Learn More</a>
                </div>
                <div class="service__card">
                    <span><i class="ri-mental-health-line"></i></span>
                    <h4>Health Check</h4>
                    <p>
                        Our thorough assessments and expert evaluations help you stay
                        proactive about your health.
                    </p>
                    <a href="#">Learn More</a>
                </div>
                <div class="service__card">
                    <span><i class="ri-hospital-line"></i></span>
                    <h4>General Dentistry</h4>
                    <p>
                        Experience comprehensive oral care with Dentistry. Trust us to keep
                        your smile healthy and bright.
                    </p>
                    <a href="#">Learn More</a>
                </div>
            </div>
</section>
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
</section>

<section class="section__container why__container">
    <div class="why__image">
        <img src="assets/choose-us.jpg" alt="why choose us" />
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
        <div class="doctors__nav">
            <span><i class="ri-arrow-left-line"></i></span>
            <span><i class="ri-arrow-right-line"></i></span>
        </div>
    </div>
    <div class="doctors__grid">
        <div class="doctors__card">
            <div class="doctors__card__image">
                <img src="assets/doctor-1.jpg" alt="doctor" />
                <div class="doctors__socials">
                    <span><i class="ri-instagram-line"></i></span>
                    <span><i class="ri-facebook-fill"></i></span>
                    <span><i class="ri-heart-fill"></i></span>
                    <span><i class="ri-twitter-fill"></i></span>
                </div>
            </div>
            <h4>Dr. Emily Smith</h4>
            <p>Cardiologist</p>
        </div>
        <div class="doctors__card">
            <div class="doctors__card__image">
                <img src="assets/doctor-2.jpg" alt="doctor" />
                <div class="doctors__socials">
                    <span><i class="ri-instagram-line"></i></span>
                    <span><i class="ri-facebook-fill"></i></span>
                    <span><i class="ri-heart-fill"></i></span>
                    <span><i class="ri-twitter-fill"></i></span>
                </div>
            </div>
            <h4>Dr. James Anderson</h4>
            <p>Neurosurgeon</p>
        </div>
        <div class="doctors__card">
            <div class="doctors__card__image">
                <img src="assets/doctor-3.jpg" alt="doctor" />
                <div class="doctors__socials">
                    <span><i class="ri-instagram-line"></i></span>
                    <span><i class="ri-facebook-fill"></i></span>
                    <span><i class="ri-heart-fill"></i></span>
                    <span><i class="ri-twitter-fill"></i></span>
                </div>
            </div>
            <h4>Dr. Michael Lee</h4>
            <p>Dermatologist</p>
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
            <p>Home</p>
            <p>About Us</p>
            <p>Work With Us</p>
            <p>Our Blog</p>
            <p>Terms & Conditions</p>
        </div>
        <div class="footer__col">
            <h4>Services</h4>
            <p>Search Terms</p>
            <p>Advance Search</p>
            <p>Privacy Policy</p>
            <p>Suppliers</p>
            <p>Our Stores</p>
        </div>
        <div class="footer__col">
            <h4>Contact Us</h4>
            <p>
                <i class="ri-map-pin-2-fill"></i> 123, London Bridge Street, London
            </p>
            <p><i class="ri-mail-fill"></i> support@care.com</p>
            <p><i class="ri-phone-fill"></i> (+012) 3456 789</p>
        </div>
    </div>
    <div class="footer__bar">
        <div class="footer__bar__content">
            <p></p>
            <div class="footer__socials">
                <span><i class="ri-instagram-line"></i></span>
                <span><i class="ri-facebook-fill"></i></span>
                <span><i class="ri-heart-fill"></i></span>
                <span><i class="ri-twitter-fill"></i></span>
            </div>
        </div>
    </div>
</footer>
</body>
</html>

