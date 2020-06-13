<?php
echo '
<div class="container margin-top-10">
    <div class="navbar">
        <h3 class="home_title margin-bottom-10"><strong>' . ucwords(str_replace('-', ' ', Session::get('current_project_description'))) . '</strong> Epics</h3>';
        echo $form->right_menu($this->projects, $this->current_project_id, 'job');
    echo '
        </div>';
        
echo '
<div class="row">
    <div class="form-group col-md-10 col-sm-12">
        <form action="/job/index" method="POST" class="form-inline">';

        if (isset($projects)) {
            echo '
            <div class="form-group filter_selection">
                <label>Project</label>
                <select name="project_id" class="form-control">
                    <option value="">All</option>';
                foreach($projects as $project) {
                    echo '<option value="'. $project['id'] .'" ' . (isset($project_id) ? ($project_id == $project['id'] ? 'selected': '') : (Session::get('current_project_id') ? (Session::get('current_project_id') == $project['id'] ? 'selected': '') : '')) . '>' . $project['description'] . '</option>';
                }
                echo '</select>
                </div>';
        }

echo '
            <div class="form-group filter_selection">
                <label>Epic Priority</label>
                <select name="priority" class="form-control">
                    <option value="">All</option>';
                    foreach ($priority_type as $value) {
                        echo '<option value="'. $value .'" ' . (isset($priority) ? ($priority == $value ? 'selected': '') : '') . '>' . $value . '</option>';
                    }
            echo '
                </select>
            </div>
            <div class="form-group filter_selection">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="">All</option>';
                    foreach ($status_type as $value) {
                        echo '<option value="'. $value .'" ' . (isset($status) ? ($status == $value ? 'selected': '') : '') . '>' . $value . '</option>';
                    }
            echo '
                </select>
            </div>
            <div class="form-group filter_selection">
                <label>Confidence Level</label>
                <select name="confidence_level" class="form-control">
                    <option value="">All</option>';
                    foreach ($confidence_level_type as $value) {
                        echo '<option value="'. $value .'" ' . (isset($confidence_level) ? ($confidence_level == $value ? 'selected': '') : '') . '>' . $value . '</option>';
                    }
                echo '
                </select>
            </div>
            <div class="form-group filter_selection">
                <label>Milestones</label>
                <select name="active_milestone" class="form-control">
                    <option value="">All</option>';
                    foreach ($active_type as $key => $value) {
                        echo '<option value="'. $key .'" ' . (isset($active_milestone) ? ($active_milestone == $key ? 'selected': '') : '') . '>' . $value . '</option>';
                    }
                echo '
                </select>
            </div>
            <input name="filter" type="hidden" value="1">
            <button class="btn btn-primary">Filter</button>
        </form>
    </div>';
if (Access::get_permission() == ('admin' || 'limited')) {
    echo '
        <div class="button_container col-md-2 col-sm-12">';
        
    if (Access::get_permission() != 'limited') {
        echo $form->button(
            array(
                'id' => 'codebase_get',
                'class' => 'no_decoration',
                'btn-class' => 'btn-primary pull-right',
                'title' => 'Get CodebaseHQ Data'
            ),
            'glyphicon-import',
            'Get CodebaseHQ Data'
        );
    }
    
    echo $form->button(
        array(
            'btn-class' => 'btn-primary pull-right btn-adjust-margin',
            'title' => 'Create a new Epic',
            'url' => 'job/add'
        ),
        'glyphicon-plus',
        'Add Epic'
    );
    echo '</div></div>';
}
    
if ($jobs) {
    echo '
    <table class="table sortable"> 
        <thead>
            <tr>
                <th>Name <span class="glyphicon glyphicon-sort gray-lighter"></span></th>
                <th>Milestone Name <span class="glyphicon glyphicon-sort gray-lighter"></th>
                <th>Epic Priority <span class="glyphicon glyphicon-sort gray-lighter"></th>
                <th>CodebaseHQ Tag <span class="glyphicon glyphicon-sort gray-lighter"></th>
                <th>Number of Tickets <span class="glyphicon glyphicon-sort gray-lighter"></th>
                <th>Confidence Level <span class="glyphicon glyphicon-sort gray-lighter"></th>
                <th title="Combined Estimated Time">Estimated Time <span class="glyphicon glyphicon-sort gray-lighter"></th>
                <th>Time Spent <span class="glyphicon glyphicon-sort gray-lighter"></th>
                <th>Status <span class="glyphicon glyphicon-sort gray-lighter"></th>
                <th class="sorter-shortDate dateFormat-ddmmyyyy">Date Added <span class="glyphicon glyphicon-sort gray-lighter"></th>
                <th colspan="4"></th>
            </tr>
        </thead>
        <tbody>';

    if (is_array($jobs) && count($jobs) > 0) {
        foreach($jobs as $job) {
            echo '
            <tr id="drag_drop_item_' . $job['id'] . '">
                <td>' . $job['name'] . '</td>
                <td>' . ($job['milestone_name'] ? $job['milestone_name'] : ' - ') . '</td>
                <td>' . $job['priority'] . '</td>
                <td>' . $job['code_base_tag'] . '</td>
                <td>' . $job['tickets'] . '</td>
                <td>' . $job['confidence_level'] . '</td>
                <td>' . $job['story_points'] . '</td>
                <td>' . $job['time_spent'] . '</td>
                <td>' . $job['status'] . '</td>
                <td>' . $form->date_time_format($job['create_at']) . '</td>
                <td>' . $form->button(array('btn-class' => 'btn-info', 'class' => 'view', 'title' => 'View details for this Epic', 'url' => 'job/view/' . $job['id']), 'glyphicon-zoom-in') . '</td>';

            if (Access::get_permission() == ('admin' || 'limited')  && $job['active_milestone'] == 1) {
                echo '
                <td>' . $form->button(array('btn-class' => 'btn-warning', 'title' => 'Copy this Epic', 'url' => 'job/copy/' . $job['id'] ), 'glyphicon-copy') . '</td>
                <td>' . $form->button(array('btn-class' => 'btn-success', 'title' => 'Modify this Epic', 'url' => 'job/edit/' . $job['id'] ), 'glyphicon-edit') . '</td>';
                if (Access::get_permission() != 'limited') {
                    echo '
                    <td>' . $form->button(array('btn-class' => 'btn-danger', 'class' => 'Permanently remove this Epic', 'title' => 'Delete this Epic', 'url' => 'job/delete/' . $job['id']), 'glyphicon-remove') . '</td>';
                }
            }
            echo '
            </tr>';
        }
    }
    echo '
        </tbody>
    </table>';
}
echo '
</div>';

?>
<script type="text/javascript">
    $('#codebase_get').on('click', function(){
        $.notify({
            message: 'Information for this Product is now being updated with data from CodebaseHQ. Once complete, this page will refresh.'
        },{
            type: 'info',
            delay: 10000,
        });
        var url = "/project/get_all_code_base_data/<?php echo Session::get('current_project_name');?>";
        $.ajax({
             type: "POST",
             url: url,
                success: function(data) {
                   if (data) {
                        $.notify({
                            message: 'CodebaseHQ data Imported Successfully.'
                        },{
                            type: 'success'
                        });
                        setTimeout(function() {location.reload();}, 2000);
                   }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $.notify({
                        message: 'There has been a problem retrieving data from CodebaseHQ.'
                    },{
                        type: 'warning',
                    });
                }
        });
    });
    
    $(window).resize(function() {
        if ($(document).width() > 1752) {
            $('.btn-adjust-margin').addClass('margin-right-5').removeClass('margin-top-5');
        } else {
            $('.btn-adjust-margin').addClass('margin-top-5').removeClass('margin-right-5');
        }
    });
    
    $(function() {
        if ($(document).width() > 1752) {
            $('.btn-adjust-margin').addClass('margin-right-5').removeClass('margin-top-5');
        } else {
            $('.btn-adjust-margin').removeClass('margin-right-5').addClass('margin-top-5');
        }
    });
</script>