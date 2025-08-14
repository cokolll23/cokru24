BX.ready(function () {
// todo перенести компонент в модуль
    $('body').on('click', '#BOOK_GRID_table tbody tr', function (e) {
        //id lists_list_elements_17
        e.preventDefault()
        if ($(e.target).parents('tr')) {
            // todo при клике на tr авто из списка  находим id элемента в GarageTable
            var iGarageTableId = $(e.target).parents('#BOOK_GRID_table tr').data('id');

            // слайдер справа выводит по ссылке на физ страницу с компонентом выводящим
            // историю обращений /bitrix/components/lab.crmcustomtab/deals.grid
            BX.SidePanel.Instance.open('/hlp/index.php?id=' + iGarageTableId);
        }
    })
});
