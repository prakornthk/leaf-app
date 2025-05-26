<?php

require __DIR__ . '/vendor/autoload.php';

// Start session for tracking shown quotes
session_start();

// Array of Thai quotes
$quotes = [
    'ความพยายามอยู่ที่ไหน ความสำเร็จอยู่ที่นั่น',
    'อย่ากลัวที่จะช้า แต่จงกลัวที่จะหยุดอยู่กับที่',
    'ทุกปัญหามีทางออก ถ้าเรามีความพยายามมากพอ',
    'ความสำเร็จไม่ได้วัดจากที่จุดหมาย แต่วัดจากทุกก้าวที่เดินไป',
    'จงเริ่มต้นวันนี้ด้วยรอยยิ้ม แล้วทุกอย่างจะดีเอง',
    'ไม่มีใครรู้ขีดจำกัดของตัวเอง จนกว่าจะได้ลองทำ',
    'ความฝันไม่มีวันตาย ถ้าเรายังไม่เลิกฝัน',
    'เส้นทางพันไมล์ เริ่มต้นจากก้าวแรกเสมอ',
    'ทำวันนี้ให้ดีที่สุด เพราะวันพรุ่งนี้อาจสายเกินไป',
    'ความสำเร็จคือการลุกขึ้นมาให้ได้ทุกครั้งที่ล้ม'
];

app()->get('/', function () {
    response()->json(['message' => 'Hello World from Leaf!']);
});

app()->get('/date', function () {
    response()->json(['message' => 'Hello World from Leaf! date: ' . date('Y-m-d H:i:s')]);
});

app()->get('/quote', function () use ($quotes) {
    // Initialize shown quotes array in session if not exists
    if (!isset($_SESSION['shown_quotes'])) {
        $_SESSION['shown_quotes'] = [];
    }

    // Reset if all quotes have been shown
    if (count($_SESSION['shown_quotes']) >= count($quotes)) {
        $_SESSION['shown_quotes'] = [];
    }

    // Get available quotes (not shown yet)
    $available_quotes = array_diff_key($quotes, array_flip($_SESSION['shown_quotes']));
    
    // Get random quote from available quotes
    $random_key = array_rand($available_quotes);
    $quote = $available_quotes[$random_key];
    
    // Add to shown quotes
    $_SESSION['shown_quotes'][] = $random_key;
    
    response()->json([
        'quote' => $quote,
        'remaining_quotes' => count($quotes) - count($_SESSION['shown_quotes'])
    ]);
});

app()->run();