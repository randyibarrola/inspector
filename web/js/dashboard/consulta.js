 function initDatePicker(){
    $('.input-daterange').datepicker({
        format: "dd/mm/yy",
        language: "es"
    });
    $('.ejecucion').datepicker({
        format: "dd/mm/yy",
        language: "es"
    });
    $('.eliminar').click(function(){
        $(this).parent().parent().parent().remove();
    });
}
    
jQuery(document).ready(function() {
    Form.init();
    initDatePicker();

    $('.agregar').click(function(e){
        var clonado = jQuery('.inspecciones').last().clone();
        $(clonado).insertAfter($('.inspecciones').last());
        $('input[name="ids[]"]').last().val(0);
        initDatePicker();
    });
});