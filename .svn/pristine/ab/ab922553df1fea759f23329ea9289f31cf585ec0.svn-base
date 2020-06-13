$(function() {
    $('#file_upload').on('change',function() {
            //get the form id data		
            var formData = new FormData();
            formData.append('file_upload', $('#file_upload')[0].files[0]);

            $.ajax({
                   url : url + "document/filter_upload_file",
                   type : 'POST',
                   data : formData,
                   processData: false,  // tell jQuery not to process the data
                   contentType: false,  // tell jQuery not to set contentType
                   success : function(data) {
                       $('.upload_message').html(data);
                   }
            });
    });

    $('.datepicker').datetimepicker({ format: 'DD/MM/YYYY HH:mm ' });

    $('.delete').on('click', function() {
        return confirm('Are you sure you want to delete?');
    });
    
    $('#minimise_menu').on('click', function() {
        $('nav.navbar').fadeToggle('slow');
    });
    
    $('table.sortable').tablesorter();
});
