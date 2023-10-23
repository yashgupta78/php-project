<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "emploee_info";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert record
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['employeeName']) && isset($_POST['employeeEmail'])) {
        $employeeName = $_POST['employeeName'];
        $employeeEmail = $_POST['employeeEmail'];

        // Insert employee data
        $sql = "INSERT INTO emploee (fname, mail) VALUES ('$employeeName', '$employeeEmail')";
        if ($conn->query($sql) === "TRUE") {
            $last_id = $conn->insert_id; // Retrieve the last inserted ID

            // Insert address data
            foreach ($_POST['addressStreet'] as $key => $value) {
                $addressStreet = $_POST['addressStreet'][$key];
                $addressCity = $_POST['addressCity'][$key];
                $addressState = $_POST['addressState'][$key];
                $addressZip = $_POST['addressZip'][$key];

                $sql = "INSERT INTO address (employee_id, street, city, state, zip) VALUES ('$last_id', '$add_line 1', '$add_line 2', '$state', '$sountry')";
                if ($conn->query($sql) !== "TRUE") {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }

            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>

<html>

<head>
    <style>
        .address {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <form id="employeeForm" method="POST" action='' name="employeeForm">

        <h2>Employee Information</h2>
        <label for="employeeName">Name:</label>
        <input type="text" id="employeeName" name="employeeName"><br><br>

        <label for="employeeEmail">Email:</label>
        <input type="text" id="employeeEmail" name="employeeEmail"><br><br>

        <h2>Addresses</h2>
        <div id="addresses">
            <!-- Address fields will be dynamically added here -->
        </div>
        <button type="button" id="addAddress">Add More Address</button><br><br>

        <input type="submit" value="Submit">
    </form>

    <script>
        // Function to add more address fields
        document.getElementById("addAddress").addEventListener("click", function() {
            const addressDiv = document.createElement("div");
            addressDiv.className = "address";
            addressDiv.innerHTML = 
            < label
            for = "addressStreet" > Street: < /label> <
                input type = "text"
            name = "addressStreet[]" > < br > < br >

                <
                label
            for = "addressCity" > City: < /label> <
                input type = "text"
            name = "addressCity[]" > < br > < br >

                <
                label
            for = "addressState" > State: < /label> <
                input type = "text"
            name = "addressState[]" > < br > < br >

                <
                label
            for = "addressZip" > Zip Code: < /label> <
                input type = "text"
            name = "addressZip[]" > < br > < br >

                <
                button type = "button"
            class = "removeAddress" > Remove Address < /button><br><br>;
            document.getElementById("addresses").appendChild(addressDiv);

            //Add functionality to remove address
            const removeAddressButtons = document.querySelectorAll(".removeAddress");
            removeAddressButtons.forEach(function(button) {
                button.addEventListener("click", function() {
                    addressDiv.remove();
                });
            });
        });

        // Prevent the default form submission for this example
        document.getElementById("employeeForm").addEventListener("submit", function(e) {
            e.preventDefault();

            // You can collect the form data and handle it via JavaScript or send it to a server.
            // Example: Serialize the form data to a JSON object
            const formData = new FormData(this);
            const employeeData = {};
            formData.forEach((value, key) => {
                if (key !== 'addressStreet' && key !== 'addressCity' && key !== 'addressState' && key !== 'addressZip') {
                    employeeData[key] = value;
                }
            });
            const addresses = [];
            document.querySelectorAll(".address").forEach(function(addressDiv) {
                const address = {};
                addressDiv.querySelectorAll("input").forEach(function(input) {
                    address[input.name] = input.value;
                });
                addresses.push(address);
            });

            employeeData.addresses = addresses;

            // Now, you can handle this employeeData object as needed.
            console.log(JSON.stringify(employeeData, null, 2));
        });
    </script>
</body>

</html>