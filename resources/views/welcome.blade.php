<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Williamsburg Therapy Group</title>



    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #f9f8f7;
            color: #4d4d4d;
        }

        header {
            background-color: #dbd4c5;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #e7e1d7;
        }

        .header-content h1 {
            margin: 0;
            font-size: 36px;
            color: #333;
        }

        .header-content h2 {
            margin: 0;
            font-size: 24px;
            color: #777;
        }

        .header-content p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #666;
        }

        main {
            padding: 20px;
        }

        .intro h2 {
            text-align: center;
            font-size: 24px;
            color: #333;
        }

        .intro p {
            text-align: center;
            font-size: 18px;
            color: #666;
        }

        .intro span {
            font-weight: bold;
            color: #333;
        }

        .filters {
            text-align: center;
            margin: 20px 0;
        }

        .filters button {
            margin: 5px;
            padding: 10px 20px;
            border: 1px solid #e7e1d7;
            background-color: #fff;
            cursor: pointer;
            color: #4d4d4d;
        }

        .filters button:hover {
            background-color: #e7e1d7;
        }

        .doctors {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .doctor-card {
            border: 1px solid #e7e1d7;
            border-radius: 5px;
            padding: 20px;
            margin: 10px;
            text-align: center;
            width: 200px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .doctor-card img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
            border: 2px solid #dbd4c5;
        }

        .doctor-card h3 {
            margin: 10px 0 5px;
            font-size: 18px;
            color: #333;
        }

        .doctor-card p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }

        .doctor-card button {
            margin: 5px;
            padding: 10px;
            border: none;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }

        .doctor-card button:hover {
            background-color: #0056b3;
        }

        .load-more {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            border: none;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }

        .load-more:hover {
            background-color: #0056b3;
        }

        footer {
            background-color: #dbd4c5;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e7e1d7;
        }

        .footer-content p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }

        .footer-content button {
            margin-top: 10px;
            padding: 10px 20px;
            border: none;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }

        .footer-content button:hover {
            background-color: #0056b3;
        }

        .footer-contact {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .footer-contact .location {
            margin: 0 20px;
        }

        .footer-contact h4 {
            margin: 0 0 5px;
            font-size: 16px;
            color: #333;
        }

        .footer-contact p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }

        .footer-links {
            margin: 20px 0;
        }

        .footer-links a {
            margin: 0 10px;
            color: #007bff;
            text-decoration: none;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <header>
        <div class="header-content">
            <h1>WILLIAMSBURG</h1>
            <h2>THERAPY GROUP</h2>
            <p>Questions? Call our New York office at (347) 697-4829 or our Austin office at (512) 866-5077</p>
        </div>
    </header>
    <main>
        <section class="intro">
            <h2>Meet Our Doctors</h2>
            <p>I am located in <span>Brooklyn</span> seeking <span>Anxiety Therapy</span></p>
            <div class="filters">
                <button>Available</button>
                <button>Mon</button>
                <button>Tue</button>
                <!-- Add more filter buttons as needed -->
                <button>$250</button>
                <button>In-Person</button>
                <button>Video</button>
            </div>
        </section>
        <section class="doctors">
            <div class="doctor-card">
                <img src="doctor1.jpg" alt="Dr. Johnny Appleseed">
                <h3>Dr. Johnny Appleseed</h3>
                <p>Licensed Psychologist | In Person & Video</p>
                <p>$250/session</p>
                <p>Next Available Appointment: Mon, April 28th, 2023 at 3:00pm ET</p>
                <button>Book Now</button>
                <button>Learn More</button>
            </div>
            <!-- Repeat doctor-card div for other doctors -->
        </section>
        <button class="load-more">Load More Doctors</button>
    </main>
    <footer>
        <div class="footer-content">
            <p>Not finding what you are looking for? That's okay, we are here to help guide you through this process.
            </p>
            <button>Call Our Coordinator</button>
        </div>
        <div class="footer-contact">
            <div class="location">
                <h4>NEW YORK</h4>
                <p>(347) 329-3637</p>
            </div>
            <div class="location">
                <h4>AUSTIN</h4>
                <p>(512) 883-4506</p>
            </div>
        </div>
        <div class="footer-links">
            <a href="#">Privacy Policy</a>
            <a href="#">In The Press</a>
            <a href="#">Media Inquiries</a>
            <a href="#">Patient Portal</a>
        </div>
    </footer>
</body>

</html>
