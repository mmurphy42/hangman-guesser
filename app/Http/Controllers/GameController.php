<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Words;

/**
 * Class GameController
 *
 * Responsible for the overall flow of the game app.
 *
 * @package App\Http\Controllers
 */
class GameController extends Controller
{
    /**
     * Processes some step of the game.
     *
     * @param Request $oRequest The HTTP request object
     * @param string $strCurWord The current word
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View The view for this request
     */
    public function show(Request $oRequest, $strCurWord)
    {
        // Get "used" query param.
        $strUsed = $oRequest->input('used');

        // If no underscores, we have a full word. Otherwise, continue guessing.
        $bFinished = strpos($strCurWord, '_') === false;

        if (!$bFinished)
        {
            // Decide how many wrong guesses there were.
            $iWrongGuesses = 0;
            if (isset($strUsed) && !empty($strUsed))
            {
                $aUsedLetters = str_split($strUsed);
                $strCurLetters = str_replace("_", "", $strCurWord);
                foreach ($aUsedLetters as $strChar)
                {
                    if (strpos($strCurLetters, $strChar) === false)
                    {
                        $iWrongGuesses++;
                    }
                }
            }

            if ($iWrongGuesses >= 8)
            {
                // Lost!
                return view('hangman.lost')->with(['strCurWord' => $strCurWord]);
            }
            else
            {
                // Return the next step of the game.
                return $this->getGame($strCurWord, $strUsed, $iWrongGuesses);
            }
        }
        else
        {
            return view('hangman.win')->with(['strCurWord' => $strCurWord]);
        }
    }

    /**
     * Returns a game view (or "lost" view) with the given parameters.
     *
     * @param string $strCurWord The current word
     * @param string $strUsed The letters used
     * @param int $iWrongGuesses The number of wrong guesses
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View The view for this game
     */
    public function getGame(
        $strCurWord,
        $strUsed,
        $iWrongGuesses
    )
    {
        // Initialize an instance of Words to process the words.
        $oWords = new Words();
        $strRegex = $this->createRegex($strCurWord, $strUsed);

        $aUsedLettersArr = array();
        foreach (str_split($strUsed) as $strLetter)
        {
            $aUsedLettersArr[$strLetter] = true;
        }
        $oWords->InitWords(strlen($strCurWord), $strRegex, $aUsedLettersArr);

        // Make a new guess.
        $strGuess = $oWords->GetBestLetter();
        if ($strGuess !== "!")
        {
            $strUsed = $strUsed . $strGuess;
            $strGuesses = (string) $iWrongGuesses;
            return view('hangman.game')
                ->with(['strGuess' => $strGuess])
                ->with(['strCurWord' => $strCurWord])
                ->with(['strUsed' => $strUsed])
                ->with(['strGuesses' => $strGuesses]);
        }
        else
        {
            return view('hangman.lost')->with(['strCurWord' => $strCurWord]);
        }
    }

    /**
     * Returns the matching regex for this current stage.
     * Any word must match this regex to be considered.
     *
     * @param string $strCurWord The current word
     * @param string $strUsedLetters The used letters
     * @return string The resulting regex
     */
    private function createRegex($strCurWord, $strUsedLetters)
    {
        $strSingleCharMatcher = '.';

        // Only exclude letters if we have used any.
        // (i.e. a "blank" space can only be matched to non-guessed letters, otherwise to any letter)
        if (strlen($strUsedLetters) > 0)
        {
            $strSingleCharMatcher = '[^' . $strUsedLetters . ']';
        }
        return '/^' . str_replace('_', $strSingleCharMatcher, $strCurWord) . '$/';
    }

    /**
     * Returns the starting index view.
     *
     * @param Request $oRequest The HTTP request object
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View The index view
     */
    public function index(Request $oRequest)
    {
        return view('hangman.landing');
    }
}
