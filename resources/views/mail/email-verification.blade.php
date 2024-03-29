<!DOCTYPE html>
<html lang="en">
<head>
    <title>QuizWiz Email Verification</title>

    <style>
        h1 {
            font-size: 2.5rem;
            line-height: 3.75rem;
            text-align: center;
        }

        p {
            line-height: 1.5rem;
            margin: 1.5rem 0;
            font-size: 1rem;
        }

        button {
            display: block;
            width: 12rem;
            height: 2.5rem;
            margin: 0 auto;
            padding: 0;
            border: 0;
            border-radius: 0.625rem;
            background-color: #4B69FD;
            text-align: center;
            color: white;
        }


        a {
            display: inline-block;
            width: 100%;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            line-height: 2.5rem;
        }

        a, a:visited, a:hover, a:active {
            color: inherit;
        }

        #main {
            height: 100vh;
            background-color: #F6F6F6;
        }

        #container {
            width: 30rem;
            margin: 0 auto;
            padding: 2.5rem 0;
        }

        #image-container {
            width: 4.5rem;
            height: 2.75rem;
            margin: 0 auto;
        }
    </style>
</head>
<div id="main">
    <div id="container">
        <div id="image-container">
            <img src="{{ asset('images/logo.png') }}" alt="QuizWiz Logo"/>
        </div>

        <h1>
            Verify your email address to get started
        </h1>

        <p>
            Hi {{ $username }},
        </p>
        <p>
            You're almost there! To complete your signup, please verify your email address.
        </p>

        <button>
            <a href="{{ $away }}?verifyUrl={{ $verifyUrl }}" target="_blank">Verify now</a>
        </button>
    </div>
</div>
</html>
