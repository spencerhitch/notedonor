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

  // Count 32nd notes
  $matches=  array();
  preg_match_all("/:32\(?[A-G][#@n]?\*/", $stave, $matches);
  $thirtyseconds = isset($matches[0]) ? count($matches[0]) : 0;

  // Count 16th notes
  $matches=  array();
  preg_match_all("/:16\(?[A-G][#@n]?\*/", $stave, $matches);
  $sixteenths = isset($matches[0]) ? count($matches[0]) : 0;

  // Count 8th notes
  $matches=  array();
  preg_match_all("/:8\(?[A-G][#@n]?\*/", $stave, $matches);
  $eighths = isset($matches[0]) ? count($matches[0]) : 0;

  // Count dotted 8th notes
  $matches=  array();
  preg_match_all("/:8d\(?[A-G][#@n]?\*/", $stave, $matches);
  $dotted_eighths = isset($matches[0]) ? count($matches[0]) : 0;

  // Count quarter notes
  $matches=  array();
  preg_match_all("/:4\(?[A-G][#@n]?\*/", $stave, $matches);
  $quarters = isset($matches[0]) ? count($matches[0]) : 0;

  // Count dotted quarter notes
  $matches=  array();
  preg_match_all("/:4d\(?[A-G][#@n]?\*/", $stave, $matches);
  $dotted_quarters = isset($matches[0]) ? count($matches[0]) : 0;

  // Count half notes
  $matches=  array();
  preg_match_all("/:h\(?[A-G][#@n]?\*/", $stave, $matches);
  $halves = isset($matches[0]) ? count($matches[0]) : 0;

  // Count dotted half notes
  $matches=  array();
  preg_match_all("/:hd\(?[A-G][#@n]?\*/", $stave, $matches);
  $dotted_halves = isset($matches[0]) ? count($matches[0]) : 0;

  // Count whole notes
  $matches=  array();
  preg_match_all("/:w\(?[A-G][#@n]?\*/", $stave, $matches);
  $wholes = isset($matches[0]) ? count($matches[0]) : 0;

  $occurances = array(
   "wholes" => $wholes,
   "dotted_halves" => $dotted_halves,
   "halves" => $halves,
   "dotted_quarters" => $dotted_quarters,
   "quarters" => $quarters,
   "dotted_eighths" => $dotted_eighths,
   "eighths" => $eighths,
   "sixteenths" => $sixteenths,
   "thirtyseconds" => $thirtyseconds,
  );
  return json_encode($occurances);
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
