<?php
class Oggetto_Sniffs_FileInstaller_NotPrefixSniff implements PHP_CodeSniffer_Sniff
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
        if(preg_match('#app/code/local/[a-zA-Z0-9]{0,}/[a-zA-Z0-9]{0,}/sql/[a-zA-Z0-9]{0,}/mysql4-#', $phpcsFile->getFilename())) {
            $message = "Installer files do not contain the prefix mysql4-";
            $phpcsFile->addError($message, $stackPtr, 'Found');
        }
    }
}
