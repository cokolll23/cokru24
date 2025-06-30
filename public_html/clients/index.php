<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$curPage = $APPLICATION->GetCurPage();
Bitrix\Main\Page\Asset::getInstance()->addCss($curPage . '/styles.css');

use Bitrix\Main\Entity\Query;
use Models\HospitalClientsTable\HospitalClientsTable as Clients;
use Bitrix\Main\Application;

$application = Application::getInstance();
$context = $application->getContext()->getRequest();

$curPage = $APPLICATION->GetCurPage();
CJSCore::Init(['popup']);

$str = 'https://cokru.ru/services/lists/16/element/0/32/?list_section_id=';

$arEx= explode('/',$str);
dump($arEx[8]);
?>
    <input class="cal-input" type="text" value="<?php $objDateTime = new DateTime();?>" name="date"
           onclick="BX.calendar({node: this, field: this, bTime: true, callback_after: dateCallback});">
<button class="btn1">Click</button>

    <script>
        // BX.element - элемент, к которому будет привязано окно, если null – окно появится по центру экрана
        let dateCallback = function(oDate) {
            alert(oDate);
        }
        BX.ready(function () {

            BX.bindDelegate(document.body, "change", 'cal-input', function (e) {
                BX.PreventDefault(e);
               alert("Привет");
            });

            var popup = BX.PopupWindowManager.create("ajaxPopup", BX('element'), {
                content: 'Контент, отображаемый в теле окна',
                width: 400, // ширина окна
                height: 200, // высота окна
                zIndex: 100, // z-index
                closeIcon: {
                    // объект со стилями для иконки закрытия, при null - иконки не будет
                    opacity: 1
                },
                titleBar: 'Заголовок окна',
                closeByEsc: true, // закрытие окна по esc
                darkMode: false, // окно будет светлым или темным
                autoHide: true, // закрытие при клике вне окна
                draggable: true, // можно двигать или нет
                resizable: true, // можно ресайзить
                min_height: 300, // минимальная высота окна
                min_width: 100, // минимальная ширина окна
                lightShadow: true, // использовать светлую тень у окна
                angle: true, // появится уголок
                overlay: {
                    // объект со стилями фона
                    backgroundColor: 'black',
                    opacity: 500
                },
                buttons: [
                    new BX.PopupWindowButton({
                        text: 'Сохранить', // текст кнопки
                        id: 'save-btn', // идентификатор
                        className: 'ui-btn ui-btn-success', // доп. классы
                        events: {
                            click: function () {
                                // Событие при клике на кнопку
                            }
                        }
                    }),
                    new BX.PopupWindowButton({
                        text: 'Копировать',
                        id: 'copy-btn',
                        className: 'ui-btn ui-btn-primary',
                        events: {
                            click: function () {

                            }
                        }
                    })
                ],
                events: {
                    onPopupShow: function () {
                        // Событие при показе окна
                        alert('onPopupShow');
                    },
                    onPopupClose: function () {
                        // Событие при закрытии окна
                        alert('onPopupClose');
                    }
                }
            });

            popup.show();
        });
    </script>

<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");