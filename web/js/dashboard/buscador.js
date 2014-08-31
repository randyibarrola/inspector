
$(document).ready(function(e) {
    $('.resultado').click(function(e){
        e.preventDefault();
            $.ajax({
            type: "GET",
            url:  urlDetalleUsuario,
            async: true,
            dataType: 'html',      
            data: {'id': $(this).data('id') },         
            success: function( data ) {
                  $('#myModal').empty().html(data);
                  $('#myModal').modal();
              },
              error: function(error){

              }
          }); 
    });
});
