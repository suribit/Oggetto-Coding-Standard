<?php
/**
 * @project  Oggetto-Coding-Standard
 * @author   Seregei Waribrus <wss.world@gmail.com>
 * @date     11/3/13
 */

class Oggetto_Sniffs_Strings_ConcatenationAlignedSniff implements PHP_CodeSniffer_Sniff
{
    private $__indexEqual;// index token equal
    private $__indexLastCorrectConcat;
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_VARIABLE, T_STRING_CONCAT);
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();


        if($tokens[$stackPtr]['code'] === T_VARIABLE) {// T_VARIABLE
            $endExpression = $phpcsFile->findNext(T_SEMICOLON, $stackPtr);
            if($phpcsFile->findNext(T_STRING_CONCAT, $stackPtr, $endExpression) !== false) {
                $this->__indexEqual = $phpcsFile->findNext(T_EQUAL, $stackPtr, $endExpression);
            }
        } else {// T_STRING_CONCAT
            if($tokens[$stackPtr]['line'] !== $tokens[$this->__indexEqual]['line']) {// equal to the concatenation (.) character and are on different lines
                if((($tokens[$stackPtr]['column'] - 1) !== $tokens[$this->__indexEqual]['column'])
                    && ($tokens[$stackPtr]['line'] !== $tokens[$this->__indexLastCorrectConcat]['line'])) {// (+) and (.) located at equal distance from the beginning of the line

                    $message = "The concatenation (.) operator is not on (=)";
                    $phpcsFile->addError($message, $stackPtr, 'Found');

                } else {
                    $this->__indexLastCorrectConcat = $stackPtr;
                }
            }
        }
    }
}
