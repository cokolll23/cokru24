<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$curPage = $APPLICATION->GetCurPage();
Bitrix\Main\Page\Asset::getInstance()->addCss($curPage . '/styles.css');

use Models\Lists\DoctorsPropertyValuesTable;

if (!$_GET['id']) {
    $APPLICATION->SetPageProperty("keywords", "Врачи сайт компания");
    $APPLICATION->SetPageProperty("description", "Врачи на сайте");
    $APPLICATION->SetPageProperty("title", "Врачи");
    $APPLICATION->SetTitle("Врачи");

    $doctors = DoctorsPropertyValuesTable::getList([
        'select' => [
            'IBLOCK_ID' => 'ELEMENT.IBLOCK_ID',
            'ELEMENT_ID' => 'IBLOCK_ELEMENT_ID',
            'LAST_NAME' => 'ELEMENT.NAME',
            'FIRST_NAME',
            'MIDDLE_NAME',
        ],

    ])->fetchAll();
    //dump($doctors);
    ?>
    <div class="doctors-block cards-list">
        <?php
        foreach ($doctors as $i => $doctor) { ?>
            <a href="<?= $curPage ?>?id=<?= $doctor['ELEMENT_ID']; ?>" id="id_<?= $doctor['ELEMENT_ID']; ?>"
               class="card">
                <?= $doctor['LAST_NAME'] . ' ' . $doctor['FIRST_NAME'] . ' ' . $doctor['MIDDLE_NAME'] ?>
            </a>
        <?php } ?>
    </div>
<?php } else {

    if ($_GET['id']) {
        $doctorId = $_GET['id'];
    } ?>
    <div>
        <a href="<?= $curPage; ?>">К списку врачей</a>
    </div>
    <?php $doctors = \Bitrix\Iblock\Elements\ElementDoctorsTable::getList([ //быстрая выборка ORM getList необходимо обозначить Символьный код и Символьный код API здесь doctors
        'select' => [
            'ID',
            'NAME',
            'FIRST_NAME_' => 'FIRST_NAME',
            'MIDDLE_NAME_' => 'MIDDLE_NAME',
            'PROCEDURE_NAME' => 'PROCEDURES.ELEMENT.NAME',
        ],
        'filter' => [
            'ID' => $doctorId
        ]
    ])->fetchAll();
    foreach ($doctors as $doctor) {
        $arDoctors[$doctor['ID']]['DOCTOR_LAST_NAME'] = $doctor['NAME'];
        $arDoctors[$doctor['ID']]['DOCTOR_FIRST_NAME'] = $doctor['FIRST_NAME_VALUE'];
        $arDoctors[$doctor['ID']]['DOCTOR_MIDDLE_NAME'] = $doctor['MIDDLE_NAME_VALUE'];
        $arDoctors[$doctor['ID']]['DOCTOR_PROCEDERES_NAME'][] = $doctor['PROCEDURE_NAME'];
    }
    $fio = $arDoctors[$doctorId]['DOCTOR_LAST_NAME'] . ' ' . $arDoctors[$doctorId]['DOCTOR_FIRST_NAME'] . ' ' . $arDoctors[$doctorId]['DOCTOR_MIDDLE_NAME'];
    $APPLICATION->SetPageProperty("title", $fio);
    $APPLICATION->SetTitle($fio);
    echo '<div class="doctor-block">';
    echo '<h3 class="doctor-name">';
    echo $fio;
    echo '</h3>';
    ?>
    Процедуры :
    <ul>
        <?php foreach ($arDoctors[$doctorId]['DOCTOR_PROCEDERES_NAME'] as $DOCTOR_PROCEDERE_NAME): ?>
            <li><?= $DOCTOR_PROCEDERE_NAME ?></li>
        <?php endforeach; ?>
    </ul>
    </div>
<?php } ?>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>