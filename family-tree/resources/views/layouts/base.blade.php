<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Family Tree</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <style>
        /* Paste the CSS for the tree here */
        .tree ul {
            padding-top: 20px;
            position: relative;
            transition: all 0.5s;
        }

        .tree li {
            float: left; /* Align the nodes */
            text-align: center;
            list-style-type: none;
            position: relative;
            padding: 20px 5px 0 5px;
            transition: all 0.5s;
        }

        /* Connecting lines */
        .tree li::before, .tree li::after {
            content: '';
            position: absolute; 
            top: 0; 
            right: 50%;
            border-top: 2px solid #ccc;
            width: 50%; 
            height: 20px;
        }

        .tree li::after {
            right: auto; 
            left: 50%;
            border-left: 2px solid #ccc;
        }

        .tree li:only-child::after, .tree li:only-child::before {
            display: none;
        }

        .tree li:only-child {
            padding-top: 0;
        }

        .tree li:first-child::before, .tree li:last-child::after {
            border: 0 none;
        }

        .tree li:last-child::before {
            border-right: 2px solid #ccc;
            border-radius: 0 5px 0 0;
        }

        .tree li:first-child::after {
            border-radius: 5px 0 0 0;
        }

        /* Nodes */
        .tree li span {
            border: 2px solid #ccc;
            padding: 5px 10px;
            text-decoration: none;
            color: #666;
            font-family: arial, verdana, tahoma;
            font-size: 11px;
            display: inline-block;
            border-radius: 5px;
            transition: all 0.5s;
        }

        .tree li span:hover {
            border: 2px solid #94a0b4;
            color: #000;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('peoples.index') }}">Family Tree</a>
        </div>
    </nav>
    <div class="container">
        @yield('content')
    </div>
    
    <script src="{{ mix('js/app.js') }}"></script>
    <!-- Sigma.js CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sigma.js/2.0.0/sigma.min.js"></script>
</body>
</html>