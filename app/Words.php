<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Class Words
 *
 * This class is responsible for handling the words in a game.
 *
 * @package App
 */
class Words extends Model
{
    const WORDS_FILE_PREFIX = 'words/words_';
    const WORDS_FILE_SUFFIX = '.txt';

    /**
     * @var array A map from each letter to the number of occurrences in the word list
     * (note! if a letter occurs more than once in a word, it's only counted once!)
     */
    private $m_aLetterOccurrencesMap;

    /**
     * Words constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->ClearWords();
    }

    /**
     * Returns the best letter given the number of occurrences.
     *
     * @return int|string The letter
     */
    public function GetBestLetter() {
        $iMax = 0;
        $strLetterMax = "!";
        foreach ($this->m_aLetterOccurrencesMap as $strLetter => $iCount)
        {
            if($iCount >= $iMax)
            {
                $strLetterMax = $strLetter;
                $iMax = $iCount;
            }
        }

        return $strLetterMax;
    }

    /**
     * Initializes the letter occurrence array.
     *
     * @param int $iLength Length of the letter
     * @param string $strRegex The regex to compare to
     * @param array $aUsedLetters Array of used letters
     * @return bool True if success, false otherwise
     */
    public function InitWords($iLength, $strRegex, $aUsedLetters) {

        // Just in case, clear the word arrays first.
        $this->ClearWords();

        // See if the file actually exists for this length.
        $strFileName = self::generateFileNameFromNumber($iLength);
        if (Storage::disk('public')->exists($strFileName))
        {
            $aContents = preg_split('/\s+/', Storage::disk('public')->get($strFileName));
            foreach ($aContents as $strLine)
            {
                // Get the word without whitespace, etc.
                $strWord = trim($strLine);

                if (
                    preg_match($strRegex, $strWord) === 1
                    && strlen($strWord) === $iLength
                )
                {
                    // Get each character of the word to iterate.
                    $aWordSplit = str_split($strWord);

                    // Bool array to hold which characters were in the word.
                    $aLettersPresent = array();

                    // For each character in the word...
                    foreach ($aWordSplit as $iPos => $strLetter)
                    {
                        // The letter was present.
                        $aLettersPresent[$strLetter] = true;
                    }

                    // Now with our array of letters present, add to the map.
                    foreach ($aLettersPresent as $strLetter => $bPresent)
                    {
                        if ($bPresent && !isset($aUsedLetters[$strLetter]))
                        {
                            if (isset($this->m_aLetterOccurrencesMap[$strLetter]))
                            {
                                $this->m_aLetterOccurrencesMap[$strLetter]++;
                            }
                            else
                            {
                                $this->m_aLetterOccurrencesMap[$strLetter] = 0;
                            }
                        }
                    }
                }
            }
        }
        else
        {
            // Error.
            echo "FILE DOESNT EXIST!!!";
            return false;
        }
        return true;
    }

    /**
     * Clears the map of letter occurrences.
     */
    private function ClearWords() {
        $this->m_aLetterOccurrencesMap = array();
    }

    /**
     * Retrieves the filename for a word of a given length.
     *
     * @param int $iNumber The number of letters in the word
     * @return string The filename corresponding to the number
     */
    private function generateFileNameFromNumber($iNumber) {
        return self::WORDS_FILE_PREFIX . strval($iNumber) . self::WORDS_FILE_SUFFIX;
    }
}
