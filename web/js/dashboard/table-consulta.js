var TableConsulta= function() {
    var initTable = function() {
        $('#data-table-consulta').dataTable().columnFilter({
            sPlaceHolder: "head:after",
            aoColumns: [
                {type: "text"}, {type: "text"}, {type: "text"}
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

