var TableCanal= function() {
    var initTable = function() {
        $('#data-table-canal').dataTable().columnFilter({
            sPlaceHolder: "head:after",
            aoColumns: [
                {type: "text"}
            ]
        });
    };

    return {
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }

            initTable();
        }
    };
}();

