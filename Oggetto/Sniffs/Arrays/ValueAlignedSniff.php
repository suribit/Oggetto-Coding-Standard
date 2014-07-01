<?php
/**
 * @project  Oggetto-Coding-Standard
 * @author   Seregei Waribrus <wss.world@gmail.com>
 * @date     11/3/13
 */

class Oggetto_Sniffs_Arrays_ValueAlignedSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(

            T_ARRAY,
            T_OPEN_SHORT_ARRAY
        );
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
        $tokens = $phpcsFile->getTokens(); // All tokens
//        echo $stackPtr . PHP_EOL;

        if($tokens[$stackPtr]['code'] == T_OPEN_SHORT_ARRAY) {
            $endExpression = $tokens[$stackPtr]['bracket_closer'];
        } else {
            $endExpression = $tokens[$stackPtr]['parenthesis_closer'];
        }

        if($phpcsFile->findNext(T_DOUBLE_ARROW, $stackPtr, $endExpression) == false)
            return;


        $indexArray = $phpcsFile->findNext([T_ARRAY, T_OPEN_SHORT_ARRAY], $stackPtr + 1, $endExpression);
        while($indexArray !== false) {
            if($tokens[$indexArray]['code'] == T_OPEN_SHORT_ARRAY) {
                $withDelete = $tokens[$indexArray]['bracket_opener'] + 1;
                $onDelete = $tokens[$indexArray]['bracket_closer'];
            } else {
                $withDelete = $tokens[$indexArray]['parenthesis_opener'] + 1;
                $onDelete = $tokens[$indexArray]['parenthesis_closer'];
            }

            for($i = $withDelete; $i < $onDelete; ++$i) {
                unset($tokens[$i]);
            }
            $indexArray = $phpcsFile->findNext([T_ARRAY, T_OPEN_SHORT_ARRAY], $onDelete, $endExpression);
        }


        $indexNumber = $maxIndexNumber = $phpcsFile->findNext([T_CONSTANT_ENCAPSED_STRING, T_LNUMBER], ($stackPtr + 1), $endExpression);
        while($indexNumber != false) {
            if((array_key_exists($indexNumber, $tokens)) && (strlen($tokens[$indexNumber]['content']) > strlen($tokens[$maxIndexNumber]['content']))) {
                $maxIndexNumber = $indexNumber;
            }

            $shift = $phpcsFile->findNext([T_CONSTANT_ENCAPSED_STRING, T_LNUMBER, T_ARRAY, T_OPEN_SHORT_ARRAY], $indexNumber + 1, $endExpression);
            if($shift !== false) {
                $indexNumber = $phpcsFile->findNext([T_CONSTANT_ENCAPSED_STRING, T_LNUMBER],
                   $shift + 1, $endExpression);
            } else {
                $indexNumber = $phpcsFile->findNext([T_CONSTANT_ENCAPSED_STRING, T_LNUMBER],
                    $indexNumber + 1, $endExpression);
            }

        }

        $indexDoubleArrow = $phpcsFile->findNext(T_DOUBLE_ARROW, $stackPtr, $endExpression);

        $indexNumber = $phpcsFile->findNext([T_CONSTANT_ENCAPSED_STRING, T_LNUMBER], $stackPtr, $indexDoubleArrow);

        $standardColumn = $tokens[$maxIndexNumber]['column'] + strlen($tokens[$maxIndexNumber]['content']) + 1;
        $maxColumn = $tokens[$maxIndexNumber]['column'];
        while($indexDoubleArrow !== false && $indexNumber !== false) {

            if(array_key_exists($indexNumber, $tokens) && array_key_exists($indexDoubleArrow, $tokens)
            ) {

                if($tokens[$indexNumber]['column'] !== $maxColumn
                    || $tokens[$indexDoubleArrow]['column'] !== $standardColumn) {

                    $message = "In the definition of multiline keys and values arrays associatiynh are aligned";
                    $phpcsFile->addError($message, $indexDoubleArrow, 'Found');

                }
            }

            $indexDoubleArrow = $phpcsFile->findNext(T_DOUBLE_ARROW, $indexDoubleArrow + 1, $endExpression);

            $indexNumber = $phpcsFile->findNext([T_CONSTANT_ENCAPSED_STRING, T_LNUMBER],
                $phpcsFile->findNext([T_CONSTANT_ENCAPSED_STRING, T_LNUMBER, T_ARRAY, T_OPEN_SHORT_ARRAY], $indexNumber + 1, $endExpression) + 1, $endExpression);

        }

    }
}
