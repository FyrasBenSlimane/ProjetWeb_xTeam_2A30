<?php
function generateQRMatrix($text) {
    $size = 29; // Matrix size
    $matrix = array_fill(0, $size, array_fill(0, $size, 0));
    
    // Generate a deterministic pattern based on text
    $hash = sha1($text);
    $binary = '';
    for($i = 0; $i < strlen($hash); $i++) {
        $binary .= str_pad(decbin(hexdec($hash[$i])), 4, '0', STR_PAD_LEFT);
    }
    
    // Add finder patterns (fixed position markers)
    for($i = 0; $i < 7; $i++) {
        for($j = 0; $j < 7; $j++) {
            // Top-left
            $matrix[$i][$j] = ($i == 0 || $i == 6 || $j == 0 || $j == 6 || ($i >= 2 && $i <= 4 && $j >= 2 && $j <= 4)) ? 1 : 0;
            // Top-right
            $matrix[$i][$size-7+$j] = ($i == 0 || $i == 6 || $j == 0 || $j == 6 || ($i >= 2 && $i <= 4 && $j >= 2 && $j <= 4)) ? 1 : 0;
            // Bottom-left
            $matrix[$size-7+$i][$j] = ($i == 0 || $i == 6 || $j == 0 || $j == 6 || ($i >= 2 && $i <= 4 && $j >= 2 && $j <= 4)) ? 1 : 0;
        }
    }
    
    // Fill the rest with data
    $idx = 0;
    for($y = 7; $y < $size-7; $y++) {
        for($x = 7; $x < $size-7; $x++) {
            if($idx < strlen($binary)) {
                $matrix[$y][$x] = intval($binary[$idx]);
                $idx++;
            }
        }
    }
    
    return $matrix;
}

function generateQRCodeHTML($text) {
    $matrix = generateQRMatrix($text);
    $size = count($matrix);
    
    $html = '<div style="background: white; padding: 20px; display: inline-block;">';
    $html .= '<div style="display: grid; grid-template-columns: repeat('.$size.', 1fr); gap: 0; background: white;">';
    
    for($y = 0; $y < $size; $y++) {
        for($x = 0; $x < $size; $x++) {
            $color = $matrix[$y][$x] ? 'black' : 'white';
            $html .= '<div style="width: 8px; height: 8px; background: '.$color.'; border: none;"></div>';
        }
    }
    
    $html .= '</div></div>';
    return $html;
}

function generateEventQRCode($eventId, $eventTitle, $date, $location) {
    $data = array(
        'type' => 'event',
        'id' => $eventId,
        'title' => $eventTitle,
        'date' => $date,
        'location' => $location,
        'timestamp' => time()
    );
    
    return generateQRCodeHTML(json_encode($data));
}