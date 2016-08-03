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

function replaceNextMatchingNote($text, $instr_val, $dur, $donor) {
  $stave_start = findStaveN($text, $instr_val, 0);
  $note_pattern = "(:" . $dur . "[A-G][#@n]?\*)";
  $chord_pattern = "(:" . $dur . "\(\S*\*\S*\))";
  $pattern =  "/" . $note_pattern . "|" . $chord_pattern . "/";
  $matches = []; 
  preg_match($pattern, $text, $matches, PREG_OFFSET_CAPTURE, $stave_start);
  $match_index =  $matches[0][1];
  $star_index = strpos($text, '*', $match_index);
  $first_portion = substr($text,0, $star_index);
  $second_portion = substr($text, $star_index+1);
  $concatenated = $first_portion . $donor . $second_portion;
  return $concatenated;
}

if( $_POST['first_name'] and $_POST['last_name'] and $_POST['instrument_number'] 
  and $_POST['note_duration'] ) {

  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];
  $instrument_number = intval($_POST['instrument_number']);
  $note_duration = $_POST['note_duration'];
  $current_text = file_get_contents('score.txt');

  $donor_name = "+" . $first_name . "_" . $last_name . "+";

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
