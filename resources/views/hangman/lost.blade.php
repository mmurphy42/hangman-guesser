<!DOCTYPE html>
<html>
<head>
    <title>Hangman</title>
    <link rel="icon" href="https://www.shareicon.net/data/128x128/2015/08/19/87559_grey_1042x1042.png">
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href="/css/hangman.css" rel="stylesheet">
    <style>
        #playAgainButton {
            background-color: #DCDCDC;
            border: none;
            color: black;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            font-weight: 900;
        }
    </style>
</head>
<body>
<h1>I lost :(</h1>
<h2>{{$strCurWord}}</h2>
<form onsubmit="playAgain(); return false">
    <input type="submit" value="Play again" id="playAgainButton">
</form>
<script>
    function playAgain() {
        window.location = "/";
    }
</script>
</body>
</html>