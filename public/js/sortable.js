function formatTables()
{
    $('.roadmap_table').each(function()
    {
        $(this).find('th').hide();
        $(this).find('td').hide();
        
        if ($(this).attr('id') == 'table-plan_of_commitment') {
            $(this).find('th.name').addClass('col-md-8');
            $(this).find('td.name').addClass('col-md-8');
            $(this).find('th').show();
            $(this).find('td').show();
        } else {
            $(this).find('th.name').addClass('col-md-8').show();
            $(this).find('td.name').addClass('col-md-8').show();
            $(this).find('th.story_points').show();
            $(this).find('td.story_points').show();
            $(this).find('th.confidence_level').show();
            $(this).find('td.confidence_level').show();
        }
        $('#story_points_999').hide();
    });
    $('.story_points_holder').each(function()
    {
        if ($(this).find('.total_ticket_time').html() * 1 < 1) {
            $(this).find('.total_ticket_time').html('0');
        }
        if ($(this).find('.total_ticket_time').html() * 1 > $(this).find('.total_story_points').html() * 1) {
            $(this).find('.total_ticket_time').css({'color' : '#DE4511'});
        } else {
            $(this).find('.total_ticket_time').css({'color' : '#333'});
        }
    });
}
$(document).ready(function()
{
     $tabs = $(".tabbable");
     
     formatTables();

     $(".connectedSortable").sortable(
    {
        //crossing table drag and drop
        connectWith: ".connectedSortable",
        placeholder: "ui-state-highlight",
        stop: function(event,ui)
        {
            if ($(this).children().length == 0) {
                $(this).addClass('sortable_drag');
            } 
            if($(ui.item).closest('tbody').children().length >0) {
                if ($(ui.item).closest('tbody').find('tr.empty_tr').length > 0) {
                    $(ui.item).closest('tbody').find('tr.empty_tr').remove();
                }
                $(ui.item).closest('tbody').removeClass('sortable_drag');
            }
            $ticket = ui.item;
            var total_ticket_time = $ticket.parents('.panel-group').find('.total_ticket_time').html();
            $ticket_id = $ticket.parents('.panel-group').attr('id').replace(/accordion-/g,"");

            var $new_milestone = $ticket.attr('data-new-milestone', $ticket_id);
            if($new_milestone) {
                $('#sync_ticket').show();
            }

            $('.connectedSortable').each(function(i,e)
            {
                var milestone_id = $(e).attr('id').replace(/drag_drop_parent_/g,"");
                var ticket_list = ($(e).sortable("toArray") + "").replace(/drag_drop_item_/g,"");
                $.post(url + 'job/update_order/' + milestone_id, {data: ticket_list}, function(res)
                {
                    //update story point value on the DOM
                    var $total_time = $('#story_points_' + milestone_id).find('.total_ticket_time');
                    var $story_points = $(e).find('.story_points');
                    var total = 0;
                    if ($story_points.length) {
                        $.each($story_points, function(i,e)
                        {
                            total += parseInt($(e).html());
                        })
                    }
                    $total_time.html(total);

                    //update the colour on the outer border of the 1st and 2nd milestone
                    var milestone_story_points = $('#story_points_' + milestone_id).find('.total_story_points').html();
                    if (milestone_story_points && !$('#milestone-' + milestone_id).siblings('div.panel-heading').hasClass('plan_of_intent')) {
                        if (milestone_story_points >= total) {
                            $(e).parents('.panel.panel-default').css({'border-color' : '#A5CB18', 'background' : '#FFF'});
                        } else if(milestone_story_points < total) {
                            $(e).parents('.panel.panel-default').css({'border-color' : '#DE4511', 'background' : '#F2DEDE'});
                        }
                    }
                    formatTables();
                });
            });
            
            //update individual ticket milestone name
            $ticket = ui.item;
            $cell = $ticket.find('.milestone_name');

            var milestone_name = $ticket.parents('.panel-group').find('.hidden_milestone_name').val();
            if (milestone_name == 'Plan of Intent') {
                milestone_name = '-';
            }
            $cell.html(milestone_name);
            formatTables();
        }
    })
    .disableSelection();
});