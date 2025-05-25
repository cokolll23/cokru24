<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$curPage = $APPLICATION->GetCurPage();
Bitrix\Main\Page\Asset::getInstance()->addCss($curPage . '/styles.css');

use Bitrix\Main\Entity\Query;
use Models\HospitalClientsTable\HospitalClientsTable as Clients;

// обьект Query
// запрос к кастомной таблице HospitalClientsTable
// получение коллекции
$q = new Query(Clients::getEntity());
$q->setSelect(array('id', 'first_name', 'contact_id', 'CONTACT.*', 'DOCTOR.*', 'PROCEDURE.*'));
$result = $q->exec(); // выполняем запрос

$collection = $result->fetchCollection(); ?>

    <div class="clintsWrapper flex">

        <? foreach ($collection as $key => $record) { ?>


            <div id="hc_<?= $record->getContactId() ?>" class="clintsWrapper_inner">
                <h4><?= 'имя фамилия: ' . $record->getFirstName() . ' ' . $record->getContact()->getLastName() ?></h4>
                <p><?= 'CONTACT_ID: ' . $record->getContactId() ?></p>
                <p><?= 'Должность: ' . $record->getContact()->getPost() ?></p>
                <p><?= 'Лечащий врач: ' . $record->getDoctor()->getName() ?></p>
                <p><?= 'Процедура: ' . $record->getProcedure()->getName() ?></p>
            </div>
        <?php } ?>
    </div>
<?// dump($collection);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");