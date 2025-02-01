<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random Users</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <style>
        body { padding: 20px; }
        .filter-links { margin: 20px 0; }
        .filter-links a { margin-right: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="my-4">Random Users</h1>

        <!-- Filter links -->
        <div class="filter-links">
            <a href="{{ route('random-users', ['gender' => 'male', 'page' => $info['page'], 'results' => $info['results']]) }}" class="btn btn-info">Male</a>
            <a href="{{ route('random-users', ['gender' => 'female', 'page' => $info['page'], 'results' => $info['results']]) }}" class="btn btn-warning">Female</a>
        </div>

        <!-- User table -->
        <table id="usersTable" class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>Country</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td><a href="{{ route('user-profile', $user['login']['username']) }}">
                        {{ $user['name']['first'] }} {{ $user['name']['last'] }}
                    </a></td>
                    <td>{{ $user['email'] }}</td>
                    <td>{{ $user['gender'] }}</td>
                    <td>{{ $user['location']['country'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <nav aria-label="User navigation">
            <ul class="pagination">
                @if ($info['page'] > 1)
                    <li class="page-item"><a class="page-link" href="{{ route('random-users', ['page' => $info['page'] - 1]) }}">Previous</a></li>
                @endif
                <li class="page-item"><a class="page-link" href="{{ route('random-users', ['page' => $info['page'] + 1]) }}">Next</a></li>
            </ul>
        </nav>
    </div>

    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                "paging": false,
                "info": false 
            });
        });
    </script>
</body>
</html>
