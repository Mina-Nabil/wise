<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Williamsburg Therapy Group</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .header, .footer {
            background-color: #ffffff;
            padding: 10px 20px;
            text-align: center;
            font-size: 14px;
        }
        .header {
            border-bottom: 1px solid #ccc;
            text-align: left;
        }
        .footer {
            border-top: 1px solid #ccc;
            font-size: 12px;
        }
        .main {
            padding: 20px;
            background: #fff;
        }
        .doctor-card {
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 10px;
        }
        .doctor-card img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .btn {
            margin-right: 5px;
            margin-bottom: 10px;
        }
        .select-wrap {
            display: inline-block;
            margin: 0 10px;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="#">BACK</a>
        <p>QUESTIONS? CALL OUR NEW YORK OFFICE AT (347) 697-4829 OR OUR AUSTIN OFFICE AT (512) 866-5077</p>
    </div>
    <div class="container main">
        <h1>Williamsburg Therapy Group</h1>
        <p>Meet our Doctors</p>
        <p>
            I am located in 
            <select class="select-wrap">
                <option value="Brooklyn">Brooklyn</option>
            </select>
            seeking 
            <select class="select-wrap">
                <option value="Anxiety Therapy">Anxiety Therapy</option>
            </select>
        </p>
        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
            <div class="btn-group mr-2" role="group" aria-label="First group">
                <button type="button" class="btn btn-secondary">Available</button>
                <button type="button" class="btn btn-secondary">Mon</button>
                <button type="button" class="btn btn-secondary">Tue</button>
                <button type="button" class="btn btn-secondary">Wed</button>
                <button type="button" class="btn btn-secondary">Thu</button>
                <button type="button" class="btn btn-secondary">Fri</button>
                <button type="button" class="btn btn-secondary">Sat</button>
                <button type="button" class="btn btn-secondary">Sun</button>
            </div>
            <div class="btn-group mr-2" role="group" aria-label="Second group">
                <button type="button" class="btn btn-secondary">11:00 AM ET</button>
                <button type="button" class="btn btn-secondary">Budget $250</button>
            </div>
            <div class="btn-group" role="group" aria-label="Third group">
                <button type="button" class="btn btn-secondary">Type</button>
                <button type="button" class="btn btn-secondary">In-Person</button>
                <button type="button" class="btn btn-secondary">Video</button>
            </div>
        </div>
        <!-- Example of doctor's cards -->
        <div class="doctor-card">
            <img src="https://via.placeholder.com/150" alt="Dr. Johnny Appleseed">
            <h2>Dr. Johnny Appleseed</h2>
            <p>Specializing in Anxiety & Depression</p>
            <p>$250/session</p>
            <button class="btn btn-primary">Book Now</button>
            <button class="btn btn-secondary">Learn More</button>
        </div>
        <div class="doctor-card">
            <img src="https://via.placeholder.com/150" alt="Dr. A">
            <h2>Dr. A</h2>
            <p>Specializing in Anxiety & Depression</p>
            <p>$250/session</p>
            <button class="btn btn-primary">Book Now</button>
            <button class="btn btn-secondary">Learn More</button>
        </div>
        <!-- Add more cards as needed -->
    </div>
    <div class="footer">
        <p>WTG</p>
        <p>CONTACT US</p>
        <p>New York | (347) 322-9637</p>
        <p>Austin | (512) 834-956</p>
    </div>
</body>
</html>