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
            font-size: 16px;
            line-height: 26px;
        }

        p {
            color: #DCDCDC;
            font-size: 16px;
            line-height: 26px;
            text-indent: 30px;
            margin: 0;
        }

        #guessButton {
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
<form onsubmit="return nextGuess()">
    <div id="guessId"></div>
    <br>
    <input type="submit" id="guessButton" value="Guess">
</form>
<br>
<h2>My guess: {{$strGuess}}</h2>
<p>Click on all the positions where this letter appears, then click "Guess." I have used {{$strGuesses}} wrong guess(es).</p>
<br>
<p>(if this letter does not appear in your word, just click "Guess")</p>
<script>
    function getName(index) {
        return "cur_letter_" + index;
    }

    function addElements() {
        var elementAbove = document.getElementById("guessId");
        for (var i = 0; i < "{{$strCurWord}}".length; i++) {
            var element = document.createElement("input");
            var thisLetter = "{{$strCurWord}}".charAt(i);
            element.id = getName(i);
            element.value = thisLetter;
            element.size = 1;
            element.readOnly = true;
            element.style.fontSize = "xx-large";
            element.style.textAlign = "center";
            if (thisLetter == "_") {
                element.addEventListener("click", function(elem) {
                    if (this.value == "_") {
                        this.value = "{{$strGuess}}";
                    } else {
                        this.value = "_";
                    }
                }, false);
            }
            elementAbove.appendChild(element);
        }
    }

    function nextGuess() {
        var nextString = "";
        for (var i = 0; i < "{{$strCurWord}}".length; i++)
        {
            var thisChar = document.getElementById(getName(i)).value;
            nextString = nextString + thisChar;
        }
        window.location = nextString + "?used=" + "{{$strUsed}}";
        return false;
    }

    addElements();
</script>
</body>
</html>