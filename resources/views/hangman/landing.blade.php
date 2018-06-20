<!DOCTYPE html>
<html>
<head>
    <title>Hangman</title>
    <link rel="icon" href="https://www.shareicon.net/data/128x128/2015/08/19/87559_grey_1042x1042.png">
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href="/css/hangman.css" rel="stylesheet">
    <style>
        form {
            color: #DCDCDC;
            font-size: 20px;
            line-height: 26px;
        }

        p {
            color: #DCDCDC;
            font-size: 20px;
            line-height: 26px;
            text-indent: 30px;
            margin: 0;
        }

        #lengthInput {
            color: #2d2d2d;
            background-color: #DCDCDC;
            text-align: center;
            font-size: 39px;
            padding: 5px 5px;
            line-height: 28px;
            font-weight: 900;
        }

        #goButton {
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
<h1>Hangman</h1>
<p>Think of any English word. The computer will try to guess it in 8 guesses.</p>
<form onsubmit="startGame(length); return false">
    <br>How many letters? <br><br>
    <input type="text" size="3" name="length" id="lengthInput"><br><br><input type="submit" value="Go!" id="goButton">
</form>

<script>
    function startGame(length) {
        var newpage = "game/";
        for (var i=0; i<(Number(length.value)); i++)
        {
            newpage = newpage + "_";
        }
        newpage = newpage+"?used=";
        window.location = newpage;
    }
</script>

</body>
</html>