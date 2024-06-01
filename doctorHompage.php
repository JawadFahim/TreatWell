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
            <li class="link"><a href="">Doctors</a></li>
            <li class="link"><a href="appointment_list.php">Your Appointments</a></li>
            <li class="link"><a href="#">Drugs</a></li>
            <li class="link"><a href="#">Health Tracker</a></li>
        </ul>
        <a href="doctorProfile.php">
            <button class="btn">Profile</button>
        </a>
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
            <a href="doctor_appointment.php" class="btn">See your Appointments</a>
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
                    <h4>General Dentistry</h4>
                    <p>
                        You can see the medicines available in our pharmacy.
                    </p>
                    <a href="Drugs.php">Medicine</a>
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

            <?php

            $username = $_SESSION["username"];
            $user_id= $_SESSION["user_id"];
            include 'connection.php';
            global $conn;

            // Fetch the data
            $sql = "SELECT common_speciality, COUNT(*) as count FROM doctorinfo GROUP BY common_speciality";
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            // Fetch all results
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

            // Prepare the data for Google Charts
            $rows = array();
            $table = array();
            $table['cols'] = array(
                array('label' => 'Speciality', 'type' => 'string'),
                array('label' => 'Number of Doctors', 'type' => 'number')
            );

            foreach($result as $r) {
                $temp = array();
                $temp[] = array('v' => (string) $r['common_speciality']);
                $temp[] = array('v' => (int) $r['count']);
                $rows[] = array('c' => $temp);
            }

            $table['rows'] = $rows;
            $jsonTable = json_encode($table);
            ?>

            <html>
            <head>
                <!--Load the Ajax API-->
                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                <script type="text/javascript">

                    // Load the Visualization API and the piechart package.
                    google.charts.load('current', {'packages':['corechart']});

                    // Set a callback to run when the Google Visualization API is loaded.
                    google.charts.setOnLoadCallback(drawChart);

                    function drawChart() {
                        // Create our data table out of JSON data loaded from server.
                        var data = new google.visualization.DataTable(<?=$jsonTable?>);
                        var options = {
                            title: 'Number of Doctors by Speciality',
                            is3D: 'true',
                            width: 800,
                            height: 600
                        };
                        // Instantiate and draw our chart, passing in some options.
                        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
                        chart.draw(data, options);
                    }
                </script>
            </head>

            <body>
            <!--Div that will hold the pie chart-->
            <div id="chart_div"></div>
            </body>
            </html>

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


