<?php

/*
 * explodeTextFileToArray($delimiter, $textFileLocation)
 * 
 * Input: 
 *      $delimiter - How to split up the file
 *      $textFileLocation - Location of the file
 * Output: Array of contents
 */
function explodeTextFileToArray($delimiter, $textFileLocation) {
    $thisString = file_get_contents($textFileLocation);
    $thisArray = explode($delimiter, $thisString);
    return $thisArray;
}
/*
 * parseTextFile($file)
 * 
 * Input: Text file location
 * Output: Text file as String
 */       
function parseTextFile($file){
    if( !$file = file_get_contents($file))
        throw new Exception('No file was found!!');
    $data = [];
    $firstLine = true;
    foreach(explode("\n", $file) as $line) {
        if($firstLine) { 
            $keys=explode('|', $line);
            $firstLine = false; 
            continue; 
        } // skip first line
        $texts = explode('|', $line);
        $data[] = array_combine($keys,$texts);
    }
    return $data;
}

/*
 * removeNewLinesFromString
 * 
 * Input: A String
 * Output: A String
 */
function removeNewLinesFromString($theString){
    //return str_replace(PHP_EOL, '', $theString);
    return preg_replace("/[\n\r]/","",$theString);  
}
 
/*
 * removeNewLinesFromTextFile($inputFileLocation, $outputFileLocation)
 * 
 * Input: File's input and output locations 
 * Output: Nothing
 */
function removeNewLinesFromTextFile($inputFileLocation, $outputFileLocation){
    file_put_contents($inputFileLocation,
        preg_replace(
            '/\R+/',
            "\n",
            file_get_contents($outputFileLocation)
        )
    );  
}

?>