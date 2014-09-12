 function initDatePicker(){
    $('.input-daterange').datepicker({
        format: "dd/mm/yy",
        language: "es",
        startDate: 'today',
        autoclose: true
    }).on('change', function(ev){
        $(this).datepicker('hide');
    });
    $('.ejecucion').datepicker({
        format: "dd/mm/yy",
        language: "es",
        startDate: 'today'
    }).on('changeDate', function(ev){
        inicio = $(this).parent().find("input[name='inicio[]']");   
        range = $(this).parent().find(".input-daterange");         
        if($(inicio).val() < $(this).val()){
           $(inicio).val($(this).val()) ;
           $(range).datepicker({startDate:$(this).val() });
           //$(range).datepicker('setStartDate','2014-11-11' );
           //$(range).datepicker('setEnDate','2014-11-11' );
        }
        $(this).datepicker('hide');
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