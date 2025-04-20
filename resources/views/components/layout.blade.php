<!DOCTYPE html>
<html lang="en">
<head>
    <title>Project Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }

        h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        label {
            font-size: 16px;
            color: #555;
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        button:active {
            background-color: #3e8e41;
        }
        .custom-select {
        font-size: 16px; /* Increase font size */
        padding: 8px 12px; /* Add padding */
        width: 100%; /* Full width */
        border: 1px solid #ccc; /* Border around the dropdown */
        border-radius: 4px; /* Rounded corners */
        background-color: #f9f9f9; /* Light background color */
        color: #333; /* Text color */
        transition: all 0.3s ease; /* Smooth transition on focus */
    }

    /* Style for the custom select dropdown when focused */
    .custom-select:focus {
        border-color: #66afe9; /* Change border color when focused */
        outline: none; /* Remove default outline */
        background-color: #fff; /* White background on focus */
    }

    /* Style for the option elements inside the custom select */
    .custom-select option {
        font-size: 16px; /* Font size for options */
        padding: 10px; /* Padding for options */
        background-color: #fff; /* Option background color */
        color: #333; /* Text color */
    }

    /* Optional: Hide the default dropdown arrow in IE */
    .custom-select::-ms-expand {
        display: none;
    }

    /* Custom arrow (for browsers supporting background images) */
    .custom-select {
        background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 6"%3E%3Cpath d="M1 1l4 4 4-4" stroke="%23333" stroke-width="2" fill="none"/%3E%3C/svg%3E');
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 10px;
    }
    </style>
</head>
<body>
<div class="container">
        {{ $slot }}
    </div>
</body>
<script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</html>

