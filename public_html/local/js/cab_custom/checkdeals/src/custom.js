BX.ready(function () {
//alert('Hi crazy');VF1LA0H53246666666
    // VF1LA0H5324321010

    $('body').on('input', 'form[name="deal_0_details_editor_form"] input[name="UF_CRM_DEAL_VIN"]', function (e) {
        //id lists_list_elements_17
        e.preventDefault()
        var _this = $(this);
        var vinVal = _this.val();
        var vinValLength = vinVal.length;
        var url = '/local/js/cab_custom/checkdeals/src/ajax_deals_list.php';
        if (vinValLength == 17) {

            var data = {
                vinVal: vinVal,
                act: 'checkDeals'
            }
            sendAjax(url, 'post', data, _this);
            sendAjaxAction();
        }


    })
});
function sendAjaxAction(){

    var request = BX.ajax.runComponentAction('lab.crmcustomtab:deals.grid', 'test', {
        mode: 'class',
        data: {
            param1: 'asd',
            sessid: BX.message('bitrix_sessid')
        }
    });

}



function sendAjax(url, method = 'post', data = {}, node_target) {
    $.ajax({
        type: method,
        url: url,
        data: data,
        dataType: 'json',
        cache: false,
        success: function (data) {
            if (data.res == 0) {

                //$(' #popup-window-content-ajaxPopup ').html(data);
                $('form[name="deal_0_details_editor_form"] input[name="UF_CRM_DEAL_VIN"]')
                    .css('border-color','red').val('').parents('div[data-cid = "UF_CRM_DEAL_VIN"]')
                    .after('<div class="block">Нельзя создать заказ с таким же VIN авто  если другой заказ не закрыт или не оплачен  </div>')
                ;
                $('.block').delay(3000).slideUp(200, function(){
                    $('div.block').remove();
                    alert('Нельзя');
                });



                console.log(node_target);

            }

        }
    })


}