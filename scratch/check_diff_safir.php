<?php
$f = fopen('docs/Data Siswa Dapodik Perkelas TA 2026-2027.xlsx', 'r');
// Compare the 22 students in Dapodik with the 15 active students
$safir22 = [
    "AISYAH RABIUL AWALIYAH PURBA",
    "Alifa Nur Zahirah",
    "ALYA JAZILA ARANI",
    "AMELIA FENETA ZULHAM",
    "AQILA AZ ZAHWA",
    "Aqilah Khanza Ramadhani",
    "Asyavira Kartika",
    "ATIQA MAGHFIRAH",
    "Azqa Aqilla Mumtaz",
    "EARLYTA ARSYFA",
    "FARADILLA RAMADANI",
    "Inaya Athifa",
    "Jahira Humairoh Manurung",
    "KHANSA FAIRUZ ZHAFIRAH S",
    "KHEYLA RASHIFA",
    "MYSHA ZAHIRA SACHI",
    "NUR ALFIYAH HAWA",
    "RHALYNSA RIZKI",
    "Salsabil Azzahra Lubis",
    "Salsabila Bilqis Pasaribu",
    "SALWA TSABITA",
    "SYIFA NAURA AFAF"
];

$active15 = [
    "AISYAH RABIUL AWALIYAH PURBA",
    "AMELIA FENETA ZULHAM",
    "AQILA AZ ZAHWA",
    "Asyavira Kartika",
    "ATIQA MAGHFIRAH",
    "Azqa Aqilla Mumtaz",
    "FARADILLA RAMADANI",
    "Jahira Humairoh Manurung",
    "KHANSA FAIRUZ ZHAFIRAH S",
    "KHEYLA RASHIFA",
    "MYSHA ZAHIRA SACHI",
    "NUR ALFIYAH HAWA",
    "RHALYNSA RIZKI",
    "Salsabil Azzahra Lubis",
    "Salsabila Bilqis Pasaribu"
];

$diff = array_diff($safir22, $active15);
echo "Siswa di Safir Dapodik yang TIDAK ADA di 15 siswa aktif:\n";
foreach ($diff as $d) {
    echo "- $d\n";
}
