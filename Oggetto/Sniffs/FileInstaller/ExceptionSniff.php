<?php
class Oggetto_Sniffs_FileInstaller_ExceptionSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_OPEN_TAG);
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if(preg_match('#app/code/local/[a-zA-Z0-9]{0,}/[a-zA-Z0-9]{0,}/sql/[a-zA-Z0-9]{0,}/#', $phpcsFile->getFilename())) {
            if($phpcsFile->findNext(T_TRY, $stackPtr) == false || $phpcsFile->findNext(T_CATCH, $stackPtr) == false) {
                $message = "Installer wrap in code within a try catch block";
                $phpcsFile->addError($message, $stackPtr, 'Found');
            }
        }
    }
}
