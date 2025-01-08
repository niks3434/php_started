<?php
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

function getFullnameFromParts($surname, $name, $patronomyc) {
    $fullName = $surname . ' ' . $name . ' ' . $patronomyc;
    return $fullName;
}

function getPartsFromFullname($fullName) {
    $valueMass = explode(' ', $fullName);
    $keysMass = ['surname', 'name', 'patronomyc'];
    $fullNameMass = array_combine($keysMass, $valueMass);

    return $fullNameMass;
}

function getShortName($fullName) {
    $fullNameMass = getPartsFromFullname($fullName);
    $shotName = $fullNameMass['name'] . ' ' . mb_substr($fullNameMass['surname'], 0, 1) . '.';

    return $shotName;
}

function getGenderFromName($fullName) {
    $fullNameMass = getPartsFromFullname($fullName);
    $sumOfGender = 0; 

    if(mb_substr($fullNameMass['patronomyc'], -3) === 'вна') {
        $sumOfGender--; 
    } elseif(mb_substr($fullNameMass['patronomyc'], -2) === 'ич') {
        $sumOfGender++;
    }


    if(mb_substr($fullNameMass['name'], -1) === 'а') {
        $sumOfGender--; 
    } elseif(mb_substr($fullNameMass['name'], -1) === 'й' || mb_substr($fullNameMass['name'], -1) === 'н') {
        $sumOfGender++;
    }


    if(mb_substr($fullNameMass['surname'], -2) === 'ва') {
        $sumOfGender--;
    } elseif(mb_substr($fullNameMass['surname'], -1) === 'в') {
        $sumOfGender++;
    }

    if($sumOfGender > 0) {
        return 1;
    } elseif($sumOfGender < 0) {
        return -1;
    } else {
        return 0;
    }
}

$count = 1;
foreach($example_persons_array as $personMass) {
    $person = getGenderFromName($personMass['fullname']);

    if($person === 1) {
        echo $count . ' ' . $personMass['fullname'] . ' - men<br/>';
    } elseif($person === -1) {
        echo $count . ' ' . $personMass['fullname'] . ' - women<br/>';
    } else {
        echo $count . ' ' . $personMass['fullname'] . ' - undefined<br/>';
    } 

    $count ++;
}

function getGenderDescription($massPeople) {
    $men = array_filter($massPeople, function($person) {
        return getGenderFromName($person['fullname']) === 1;
    });

    $women = array_filter($massPeople, function($person) {
        return getGenderFromName($person['fullname']) === -1;
    });

    $undefinedGender = array_filter($massPeople, function($person) {
        return getGenderFromName($person['fullname']) === 0;
    });

    $countFull = count($men) + count($women) + count($undefinedGender);

    $percentMen = count($men) / $countFull * 100;
    $percentWomen = count($women) / $countFull * 100;
    $percentUndefinedGender = count($undefinedGender) / $countFull * 100;

    $genderData = [round($percentMen, 1), round($percentWomen, 1), round($percentUndefinedGender, 1)];

    return $genderData;
}

$genderMass = getGenderDescription($example_persons_array);

echo "<h3>Гендерный состав аудитории:</h3>";
echo "<h3>----------------------------------------</h3>";
echo "Мужчины: " . $genderMass[0] . '%' . "<br/>";
echo "Женщины: " . $genderMass[1] . '%' . "<br/>";
echo "Не удалось определить: " . $genderMass[2] . '%' . "<br/>";


function getPerfectPartner($surname, $name, $patronomyc, $massPeople) {
    $surname = mb_substr(mb_strtoupper($surname), 0, 1). mb_substr(mb_strtolower($surname), 1);
    $name = mb_substr(mb_strtoupper($name), 0, 1). mb_substr(mb_strtolower($name), 1);;
    $patronomyc = mb_substr(mb_strtoupper($patronomyc), 0, 1). mb_substr(mb_strtolower($patronomyc), 1);;

    $fullName = getFullnameFromParts($surname, $name, $patronomyc);
    $gender = getGenderFromName($fullName);

    $random = random_int(0, count($massPeople)-1);
    $choiceGender = getGenderFromName($massPeople[$random]['fullname']);

    while($gender === $choiceGender || $choiceGender === 0) {
        $random = random_int(0, count($massPeople)-1);
        $choiceGender = getGenderFromName($massPeople[$random]['fullname']); 
        $choiceName = $massPeople[$random]['fullname'];
    }

    $pair = [getShortName($fullName), getShortName($choiceName)];

    return $pair;
}

function randomPercent($min, $max) {
    $randomFloat = $min + mt_rand() / mt_getrandmax() * ($max - $min);
    return $randomFloat;
}

$pairMass = getPerfectPartner('иванов', 'иВАн', 'ИВАНОВИЧ', $example_persons_array);

echo "<br/><br/><br/>" . $pairMass[0] . ' + ' . $pairMass[1] . ' = <br/>';
echo '♡ Идеально на ' . round(randomPercent(50, 100), 2) . '% ♡';
