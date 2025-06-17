BX.ready(function () {
//alert('Hi crazy');

    $('body').on('click', '#lists_list_elements_17 a', function (e) {
        //id lists_list_elements_17
        e.preventDefault()

        var clickAttr = $(e.target).attr('href');
       // alert($(e.target).attr('href'));
        var url = '/local/ajax/registration_for_procedures_form.php';
        var data = {act: 'form', iblockDoctorsId: 17, atr: clickAttr};
        var node_target = 'ajaxPopup';// куда вставлять респонс

        var popup = BX.PopupWindowManager.create("ajaxPopup", BX('element'), {
            content: 'Контент, отображаемый в теле окна',
            width: 400, // ширина окна
            height: 200, // высота окна
            zIndex: 100, // z-index
            closeIcon: {
                // объект со стилями для иконки закрытия, при null - иконки не будет
                opacity: 1
            },
            titleBar: 'Записаться на процедуру к доктору',
            closeByEsc: true, // закрытие окна по esc
            darkMode: false, // окно будет светлым или темным
            autoHide: true, // закрытие при клике вне окна
            draggable: true, // можно двигать или нет
            resizable: true, // можно ресайзить
            min_height: 300, // минимальная высота окна
            min_width: 100, // минимальная ширина окна
            lightShadow: true, // использовать светлую тень у окна
            angle: true, // появится уголок
            content: '<form id="popup" class="popup-inner" style="z-index:10000;position:absolute;width:250px;height:100px; text-align: center;">' +

                '<input id="docId" type="hidden" value="' + clickAttr + '" name="docId">' +

                '<input id="fio" placeholder="ФИО" type="text" value="" name="fio">' +
                '<input id="date" type="text" value="03.02.2015" name="date" onclick="BX.calendar({node: this, field: this, bTime: true});">\n' +
                '</form>',
            overlay: {
                // объект со стилями фона
                backgroundColor: 'black',
                opacity: 500
            },
            buttons: [
                new BX.PopupWindowButton({
                    text: 'Сохранить', // текст кнопки
                    id: 'save-btn_' + clickAttr, // идентификатор
                    className: 'ui-btn ui-btn-success', // доп. классы
                    events: {
                        click: function () {
                            // Событие при клике на кнопку

                            let docId = $('form#popup #docId').val();
                            let fio = $('form#popup #fio').val();
                            let date = $('form#popup #date').val();
                            data= {act:'add',docId:docId,fio:fio,date:date};

                            sendAjax(url, 'post', data);
                           // popup.destroy();
                        }
                    }
                }),
                new BX.PopupWindowButton({
                    text: 'Отмена',
                    id: 'copy-btn',
                    className: 'ui-btn ui-btn-primary',
                    events: {
                        click: function () {
                            popup.destroy();

                        }
                    }
                })
            ],
            events: {
                onPopupShow: function () {
                    console.log(popup);
                    sendAjax(url, 'post', data, node_target);
                },
                onPopupClose: function () {
                    // Событие при закрытии окна
                    popup.destroy();
                }
            }
        });
        popup.show();
    })
});

function sendAjax(url, method = 'post', data = {}, node_target = '') {
    $.ajax({
        type: method,
        url: url,
        data: data,
        dataType: 'json',
        cache: false,
        success: function (data) {
            if (data.success){
                let strSuccess = ` Уважаемый ${data.fio} , вы записались на процедуру ${data.procedura} `;
            }
            $('#' + node_target + ' #popup-window-content-ajaxPopup').html(strSuccess);

        }
    })


}