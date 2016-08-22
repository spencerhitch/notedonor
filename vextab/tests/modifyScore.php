<?php
/* A function that when given FIRSTNAME, LASTNAME, INSTRUMENT_NUMBER, NOTE_DURATION and TEXT, 
 * returns a MODIFIED_VERSION of TEXT with FIRSTNAME and LASTNAME stored as the DONOR_NAME for 
 * the $next note belonging to INSTRUMENT_NUMBER with NOTE_DURATION.
 */

function L($s){
  error_log($s. "\n", 3, "./errors.log");
}

function findStaveN($s, $n, $start){
  $start =  strpos($s, "stave ",$start) + 6;
  if ($n == 1) {
    return $start;
  }
  return findStaveN($s, $n-1, $start); 
}

function chordTakeover($s){
  if (strpos($s, '*') >= strpos($s,')')){
    return $s;
  } else {
    return chordTakeover(substr_replace($s, "", strpos($s, '*'),1));
  }
}

function replaceNextMatchingNote($text, $instr_val, $dur, $donor) {
  $stave_start = findStaveN($text, $instr_val, 0);
  $pattern = "/:" . $dur . "\(?[A-G][#@n]?\*/";
  $matches = []; 
  preg_match($pattern, $text, $matches, PREG_OFFSET_CAPTURE, $stave_start);
  $match_index =  $matches[0][1];
  $star_index = strpos($text, '*', $match_index);
  $first_portion = substr($text,0, $star_index);
  $second_portion = substr($text, $star_index+1);
  if (strpos($matches[0][0], '(') !== false) {
    $second_portion = chordTakeover($second_portion);
  }
  $concatenated = $first_portion . $donor . $second_portion;
  return $concatenated;
}

if( $_POST['name'] and $_POST['instrument_number'] 
  and $_POST['note_duration'] ) {

  $name= $_POST['name'];
  $instrument_number = intval($_POST['instrument_number']);
  $note_duration = $_POST['note_duration'];
  $current_text = file_get_contents('score.txt');

  $donor_name = "+" . $name . "+";

  $new_text = replaceNextMatchingNote($current_text, 
                                      $instrument_number, 
                                      $note_duration,
                                      $donor_name
                                     );

  $file = fopen("score.txt","w");
  fwrite($file,$new_text);

exit();
}

?>
