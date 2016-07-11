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

function findNextValidNote($modify, $note_duration) {
  //Find $next duration-specifier matching note_duration and the specifier after that
  //If there's no mute between duration specifiers, find $next duration-specifier and repeat

  // Match the note duration
  $start = strpos($modify["thenOn"], ":" + $note_duration) + 1;
  $eol = strpos($modify["thenOn"], "stave");

  // Break if there's no more matching note_durations or if we pass the end of the line
  while ($start < $eol and $start >= 0 ) {

    // Find the $next note_duration
    $next = strpos($modify["thenOn"], ":", $start);
    if ($next <= 0) {
      $next = substr($modify["thenOn"], $eol);
    }

    if (strpos(substr($modify["thenOn"], $start, $next), "*") >= 0) {
      $result = [
        "cut" => $modify["cut"] + $start, 
        "thenOn" => substr($modify["thenOn"], $start)
      ];
//        error_log("THENON in findNextValidNote: " . substr($result["thenOn"], 0,300));
        return $result;
    }
    $start = strpos($modify["thenOn"], ":" + $note_duration, $start) + 1;
  }
  // TODO: THIS IS a big Error if it happens
  error_log("No more notes of that duration.");
}

function replaceNextMuteWithDonor($s, $d) {
  $open_paren = strpos($s, '(');
  $close_paren = strpos($s, ')');
  $first_asterisk = strpos($s, '*');
  if ($first_asterisk > $open_paren and $first_asterisk < $close_paren) {
    while ($first_asterisk > $open_paren and $first_asterisk < $close_paren) {
      $s = preg_replace('/\*/',$d,$s,1);
      $first_asterisk = strpos($s, '*');
      $open_paren = strpos($s,'(');
      $close_paren = strpos($s,')');
    }
    error_log("S in replaceNextMuteWithDonor: " . substr($s,0,300));
    return $s;
  } else {
    $result= preg_replace('/\*/',$d,$s,1);
    return $result;
  }
}

if( $_POST['first_name'] and $_POST['last_name'] and $_POST['instrument_number'] 
  and $_POST['note_duration'] and $_POST['current_text']) {

  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];
  $instrument_number = $_POST['instrument_number'];
  $note_duration = $_POST['note_duration'];
  $current_text = $_POST['current_text'];

  $donor_name = "+" . $first_name . "_" . $last_name . "+";

  $modify = findStaveN($current_text, intval($instrument_number), 0);
  $modify = findNextValidNote($modify, $note_duration);
  $new_content = substr($current_text, 0, $modify["cut"]) .
    replaceNextMuteWithDonor($modify["thenOn"], $donor_name);

  $file = fopen("score.txt","w");
  fwrite($file,$new_content);

exit();
}

?>
