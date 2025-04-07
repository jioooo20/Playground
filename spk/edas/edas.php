<?php
// Import data (example: from a CSV file)
$data = array_map('str_getcsv', file('a.csv'));
// Transpose the data
$data_avs = array_map(null, ...$data);


// print_r($data_avs);
// 1. Matriks keputusan + AV
for ($i = 0; $i < count($data_avs); $i++) {
    for ($j = 0; $j < count($data_avs[$i]); $j++) {
        $av = array_sum($data_avs[$i]) / count($data_avs[$i]);
    }
    $avs[$i] = $av;
}
//print matriks keputusan + av
// print_r("Matriks Keputusan + AV\n");
// for ($i = 0; $i < count($data); $i++) {
//     printf("%5s","No".($i + 1) . ":");
//     for ($j=0; $j <  count($data[$i]); $j++) { 
//         printf("%5s", $data[$i][$j]);
//     }
//     print_r("\n");
// }
// printf("%5s", "AV");
// foreach ($avs as $index => $av) {
//     printf("%5s", "AV" . ($index + 1));
// }
// echo "\n";
// printf("%5s", "");
// foreach ($avs as $av) {
//     printf("%5s", $av);
// }
// browser
echo "<h3>Matriks Keputusan + AV</h3>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>No</th>";
for ($j = 0; $j < count($data[0]); $j++) {
    echo "<th>Col" . ($j + 1) . "</th>";
}
echo "</tr>";

for ($i = 0; $i < count($data); $i++) {
    echo "<tr>";
    echo "<td>No" . ($i + 1) . "</td>";
    for ($j = 0; $j < count($data[$i]); $j++) {
        echo "<td>" . $data[$i][$j] . "</td>";
    }
    echo "</tr>";
}

echo "<tr>";
echo "<td>AV</td>";
foreach ($avs as $av) {
    echo "<td>" . number_format($av, 3) . "</td>";
}
echo "</tr>";
echo "</table>";

//2. PDA NDA (full benefit, cost nya nanti ditimpa di bawah)
//PDA //NDA
for ($i = 0; $i < count($data); $i++) {
    for ($j = 0; $j < count($data[$i]); $j++) {
        $pda[$i][$j] = ($data[$i][$j] - $avs[$j]) / $avs[$j];
    }
}
for ($i = 0; $i < count($data); $i++) {
    for ($j = 0; $j < count($data[$i]); $j++) {
        $nda[$i][$j] = ($avs[$j] - $data[$i][$j]) / $avs[$j];
    }
}

//cost PDA //cost NDA
for ($i = 0; $i < count($data); $i++) {
    for ($j = 0; $j < count($data[$i]); $j++) {
        if ($j == 1 || $j == 7) { // kolom 2 dan 8 (start 0)
            $pda[$i][$j] = ($avs[$j] - $data[$i][$j]) / $avs[$j];
        }
    }
}
for ($i = 0; $i < count($data); $i++) {
    for ($j = 0; $j < count($data[$i]); $j++) {
        if ($j == 1 || $j == 7) { // 
            $nda[$i][$j] = ($data[$i][$j] - $avs[$j]) / $avs[$j];
        }
    }
}

// print PDA dan hilangin minus jadi 0
// for ($i = 0; $i < count($pda); $i++) {
//     printf("%5s", "No" . ($i + 1) . ":");
//     for ($j = 0; $j < count($pda[$i]); $j++) {
//         $fixPDA[$i][$j] = $pda[$i][$j] >= 0 ? $pda[$i][$j] : 0;
//         printf("%7.3f", $fixPDA[$i][$j]);
//     }
//     print_r("\n");
// }
//browser
echo "<h3>PDA</h3>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>No</th>";
for ($j = 0; $j < count($pda[0]); $j++) {
    echo "<th>Col" . ($j + 1) . "</th>";
}
echo "</tr>";

for ($i = 0; $i < count($pda); $i++) {
    echo "<tr>";
    echo "<td>No" . ($i + 1) . "</td>";
    for ($j = 0; $j < count($pda[$i]); $j++) {
        $fixPDA[$i][$j] = $pda[$i][$j] >= 0 ? $pda[$i][$j] : 0;
        echo "<td>" . number_format($fixPDA[$i][$j], 3) . "</td>";
    }
    echo "</tr>";
}
echo "</table>";

// print NDA dan hilangin minus jadi 0
// for ($i = 0; $i < count($nda); $i++) {
//     // printf("%5s", "No" . ($i + 1) . ":");
//     for ($j = 0; $j <  count($nda[$i]); $j++) {
//         $fixNDA[$i][$j] = $nda[$i][$j] >= 0 ? $nda[$i][$j] : 0;
//         // printf("%7.3f", $fixNDA[$i][$j]);
//     }
//     // print_r("\n");
// }
//browser
echo "<h3>NDA</h3>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>No</th>";
for ($j = 0; $j < count($nda[0]); $j++) {
    echo "<th>Col" . ($j + 1) . "</th>";
}
echo "</tr>";

for ($i = 0; $i < count($nda); $i++) {
    echo "<tr>";
    echo "<td>No" . ($i + 1) . "</td>";
    for ($j = 0; $j < count($nda[$i]); $j++) {
        $fixNDA[$i][$j] = $nda[$i][$j] >= 0 ? $nda[$i][$j] : 0;
        echo "<td>" . number_format($fixNDA[$i][$j], 3) . "</td>";
    }
    echo "</tr>";
}
echo "</table>";


// print_r(count($fixPDA[0]));
//3. SP / SN
//import data bobot
$kribot = array_map('str_getcsv', file('b.csv'));
$bobot = array_column($kribot, 1);
//SP / SN jadi 1 hitungan
$sptemp = 0;
$nptemp = 0;
for ($i = 0; $i < count($fixPDA); $i++) { //20
    for ($j = 0; $j < count($fixPDA[$i]); $j++) { //8
        $sptemp += ($fixPDA[$i][$j] * $bobot[$j]);
        $nptemp += ($fixNDA[$i][$j] * $bobot[$j]);
    }
    $sp[$i] = $sptemp;
    $sn[$i] = $nptemp;
    $sptemp = 0;
    $nptemp = 0;
}

//print SP dan SN
// for ($i=0; $i < count($sp); $i++) { 
//     printf("%5s", "SP" . ($i + 1) . ":");
//     printf("%7.3f", $sp[$i]);
//     printf("%10s", "SN" . ($i + 1) . ":");
//     printf("%7.3f", $sn[$i]);
//     print_r("\n");
// }
//browser
echo "<h3>SP dan SN</h3>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>SP</th><th>Value</th><th></th><th>SN</th><th>Value</th></tr>";
for ($i = 0; $i < count($sp); $i++) {
    echo "<tr>";
    echo "<td>SP" . ($i + 1) . "</td>";
    echo "<td>" . number_format($sp[$i], 3) . "</td>";
    echo "<td></td>";
    echo "<td>SN" . ($i + 1) . "</td>";
    echo "<td>" . number_format($sn[$i], 3) . "</td>";
    echo "</tr>";
}
echo "</table>";

//4. Normalisasi SP dan SN
// NSP = sp / (max (sp))
// NSN = 1 - (sn / (max (sn)))
for ($i = 0; $i < count($sp); $i++) {
    $maxsp = max($sp);
    $maxsn = max($sn);
    $nsp[$i] = $sp[$i] / $maxsp;
    $nsn[$i] = 1 - ($sn[$i] / $maxsn);
    // echo "<br>SP" . ($i + 1) . " = " . $sp[$i] . " / " . $maxsp . " = " . number_format($nsp[$i], 3); //hitung rapi
    // echo "<br>SN" . ($i + 1) . " = " . $sn[$i] . " / " . $maxsn . " = " . number_format($nsn[$i], 3);
}
//browser 
echo "<h3> Normalisasi SP dan SN</h3>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>NSP</th><th>Value</th><th></th><th>NSN</th><th>Value</th></tr>";
for ($i = 0; $i < count($nsp); $i++) {
    echo "<tr>";
    echo "<td>SP" . ($i + 1) . "</td>";
    echo "<td>" . number_format($nsp[$i], 3) . "</td>";
    echo "<td></td>";
    echo "<td>SN" . ($i + 1) . "</td>";
    echo "<td>" . number_format($nsn[$i], 3) . "</td>";
    echo "</tr>";
}
echo "</table>";

//5. Alternatif Skor
//AS = 1/2 * (NSP + NSN) 
for ($i = 0; $i < count($nsp); $i++) {
    $as[$i][0] = "AS" . ($i + 1); //nama alt
    for ($j = 0; $j < 2; $j++) { // alt sama skor
        $as[$i][1] = (1 / 2) * ($nsp[$i] + $nsn[$i]);
    }

    // echo "<br>AS" . ($i + 1) . " = (1/2) * (" . $nsp[$i] . " + " . $nsn[$i] . ") = " . number_format($as[$i], 3);
}
//browser
echo "<h3>Alternatif Skor</h3>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>Alternatif</th><th>AS</th></tr>";
for ($i = 0; $i < count($as); $i++) {
    for ($j=0; $j < count($as[$i]); $j++) { 
        echo "<tr>";
        echo "<td>" . $as[$i][0] . "</td>";
        echo "<td>" . number_format($as[$i][1], 3) . "</td>";
        echo "</tr>";
    }
}
echo "</table>";

//6. Ranking (sort desc)
usort($as, function ($a, $b) {
    return $b[1] <=> $a[1];
});
$sortAS = $as;

//browser
echo "<h3>Ranking</h3>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>Alternatif</th><th>AS</th></tr>";
for ($i = 0; $i < count($as); $i++) {
        echo "<tr>";
        echo "<td>" . $sortAS[$i][0] . "</td>";
        echo "<td>" . number_format($sortAS[$i][1], 3) . "</td>";
        echo "</tr>";
}
echo "</table>";
