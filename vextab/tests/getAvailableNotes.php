<?php
/* A function that when given FIRSTNAME, LASTNAME, INSTRUMENT_NUMBER, NOTE_DURATION and TEXT, 
 * returns a MODIFIED_VERSION of TEXT with FIRSTNAME and LASTNAME stored as the DONOR_NAME for 
 * the $next note belonging to INSTRUMENT_NUMBER with NOTE_DURATION.
 */

function findStaveN($s, $n, $cut){
  if ($n < 1) {
    return;
  }
  $start =  strpos($s, "stave ") + 6;
  $sub = substr($s, $start);
  if ($n == 1) {
    //TODO, doesn't work for final instrument
    return [
      "thenOn" => $sub, 
      "cut" => $cut+$start,
    ];
  }
  return findStaveN($sub, $n-1, $cut+$start); 
}

function findAvailableNotes($s, $n){

}

if( $_POST['instrument_number'] ) {

  $instrument_number = $_POST['instrument_number'];
  $score = file_get_contents('./score.txt');

  //Count the number of available notes of every type exist for the instrument
  $available_notes = findAvailableNotes($score, $instrument_number);

  //TODO Query SQL for queued purchases

  //Subtract purchases from each category. If # == 0 don't display.

  

  echo "Test";

exit();
}

?>
