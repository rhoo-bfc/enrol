<?php

function to_uk_date( $date ) {
    
    return (new \Carbon\Carbon( $date ) )->format('d/m/Y');
}

function displayFriendly( $display ) {
    
    return ucfirst( strtolower( $display  ) );
}