<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .profile-container {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
        .profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-top: 20px;
        }
        .icon-bar i {
            font-size: 20px; /* Icon size */
            margin: 10px;
            color: #666; /* Icon color */
            cursor: pointer;
        }

        .icon-bar i:hover {
            color: #3498db; /* Hover color change */
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <img src="{{ $user['picture']['large'] }}" alt="Profile Image" class="profile-image">
        <p>Hi, My name is </p>
        <h2>{{ $user['name']['first'] }} {{ $user['name']['last'] }}</h2>
        <div class="icon-bar">
            <i class="fa fa-phone"></i>
            <i class="fa fa-envelope"></i>
            <i class="fa fa-map-marker-alt"></i>
            <i class="fa fa-globe"></i>
            <i class="fa fa-bell"></i>
        </div>
    </div>
</body>
</html>
