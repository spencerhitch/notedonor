<?php

function findStaveN($s, $n, $start){
  $start =  strpos($s, "stave ",$start) + 6;
  if ($n == 1) {
    return $start;
  }
  return findStaveN($s, $n-1, $start); 
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
  $concatenated = $first_portion . $donor . $second_portion;
  return $concatenated;
}

function modifyScore($name, $instrument, $duration){
  $current_text = file_get_contents('score.txt');
  $donor_name = "+" . $name . "+";
  $new_text = replaceNextMatchingNote($current_text, $instrument, $duration, $donor_name);
  $file = fopen("score.txt","w");
  fwrite($file,$new_text);
}

?>
