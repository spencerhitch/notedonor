<?php
/* A function that when given FIRSTNAME, LASTNAME, INSTRUMENT_NUMBER, NOTE_DURATION and TEXT, 
 * returns a MODIFIED_VERSION of TEXT with FIRSTNAME and LASTNAME stored as the DONOR_NAME for 
 * the $next note belonging to INSTRUMENT_NUMBER with NOTE_DURATION.
 */

function findStaveN($s, $n, $start){
//  if ($n < 1) {
//    //TODO, doesn't work for final instrument
//    return;
//  }
  $start =  strpos($s, "stave ",$start) + 6;
  $length =  strpos($s, "stave ", $start) - $start;
  if ($n == 1) {
    if ($length > 0) {
      return substr($s, $start, $length);
    }
    return substr($s, $start);
  }
  return findStaveN($s, $n-1, $start); 
}

function findAvailableNotes($s, $n){
  $stave = findStaveN($s,$n,0);
  return $stave;
}

if( $_POST['instrument_number'] ) {

  $instrument_number = $_POST['instrument_number'];
  $score = file_get_contents('./score.txt');

  //Count the number of available notes of every type exist for the instrument
  $available_notes = findAvailableNotes($score, $instrument_number);

  //TODO Query SQL for queued purchases

  //Subtract purchases from each category. If # == 0 don't display.

  echo $available_notes;

exit();
}

?>
