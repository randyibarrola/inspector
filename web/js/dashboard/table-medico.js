var TableMedico = function() {
    var initTable = function() {
        $('#data-table-medico').dataTable().columnFilter({
            sPlaceHolder: "head:after",
            aoColumns: [
                {type: "text"}, {type: "text"}, {type: "text"}, {type: "text"}, {type: "text"}
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
