BX.ready(function () {
//alert('Hi crazy');

    $('body').on('click', '#BOOK_GRID_table tbody tr', function (e) {
        //id lists_list_elements_17
        e.preventDefault()
        if ($(e.target).parents('tr')){
           var iGarageTableId = $(e.target).parents('#BOOK_GRID_table tr').data('id');
        }
        BX.SidePanel.Instance.open("/hlp/index.php?id="+ iGarageTableId);
    })
});

function sendAjax(url, method = 'post', data = {}, node_target = '') {
    $.ajax({
        type: method,
        url: url,
        data: data,
        dataType: 'html',
        cache: false,
        success: function(data) {
            //if (data.success) {

                $(' #popup-window-content-ajaxPopup ').html(data);

           // }

        }
    })


}