<?php

/**
 * display a date in the uk format
 * 
 * @param string $date
 * @return string
 */
function to_uk_date( $date ) {
    
    return (new \Carbon\Carbon( $date ) )->format('d/m/Y');
}

/**
 * Upper case first letter of a word(s)
 * 
 * @param string $display
 * @return string
 */
function displayFriendly( $display ) {
    
    return ucfirst( strtolower( $display  ) );
}