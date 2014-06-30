<?php
/**
 * @project  Oggetto-Coding-Standard
 * @author   Seregei Waribrus <wss.world@gmail.com>
 * @date     11/3/13
 */


class Oggetto_Sniffs_Strings_SingleQuotationMarksSniff implements PHP_CodeSniffer_Sniff {
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_CONSTANT_ENCAPSED_STRING);
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
        $content = $tokens[$stackPtr]['content'];
        if($content[0] === '"') {
            $message = "Literal strings are enclosed in ' single quotes '";
            $phpcsFile->addError($message, $stackPtr, 'Found');
        }
    }
}
