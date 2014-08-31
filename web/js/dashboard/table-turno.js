var TableTurno = function() {
    var initTable = function() {
        $('#data-table-turno').dataTable().columnFilter({
            sPlaceHolder: "head:after",
            aoColumns: [
                {type: "text"}, {type: "text"}, {type: "number"},null, {type: "text"}, {type: "text"}, null
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


$('.imagen').click(function(e){
    e.preventDefault();
    $('#nombre').text($(this).data('nombre'));    
    $('#imagen').attr('src', $(this).data('src'));
    $('#myModal').modal();

});

