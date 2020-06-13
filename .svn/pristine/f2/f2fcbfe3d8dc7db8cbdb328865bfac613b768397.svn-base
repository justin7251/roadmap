<?php
echo '
<div class="container margin-top-10 full-height">
    <div class="navbar">
        <h3 class="home_title"><strong>Product Delivery Timeline</strong> <i>' . $year . '</i></h3>';
        echo $form->right_menu($this->projects, $this->current_project_id, 'home');
$rows = array();
$previous = array();
foreach ($project_details as $project_id => $project) {
    foreach ($project['milestones'] as $milestone) {
        $start_date = explode('-', substr($milestone['start_date'], 0, 10));
        $start_date[1]--;
        $start_date = implode(', ', $start_date);
        
        $actual_date = explode('-', substr($milestone['actual_date'], 0, 10));
        $actual_date[1]--;
        $actual_date = implode(', ', $actual_date);
        
        $rows[] = "['" . $milestone['id'] . "', '" . addslashes($milestone['name']) . " (" . $milestone['story_points'] . ")', '" . addslashes($project['name']) . "', "
            . "new Date(" . $start_date . "), new Date(" . $actual_date . "), "
            . "null, " . ($milestone['progress'] * 1) . ", " . ($link && array_key_exists($project['name'], $previous) ? "'" . $previous[$project['name']] . "'" : "null") . "]";
            
        $previous[$project['name']] = $milestone['id'];
    }
}
echo '</div>';

    echo '
        <div class="button_container pull-right">';
        
        echo $form->button(
            array(
                'id' => 'codebase_get',
                'class' => 'no_decoration',
                'btn-class' => 'btn-primary',
                'title' => 'Get CodebaseHQ Data'
            ),
            'glyphicon-import',
            'Get CodebaseHQ Data'
        );
        echo $form->button(
            array(
                'btn-class' => 'btn-success',
                'title' => 'Configure',
                'data-toggle' => 'modal',
                'data-target' => '#configure'
            ),
            'glyphicon-cog',
            ''
        );

echo '<!-- Modal -->
    <div id="configure" class="modal fade">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Configure Delivery Timeline</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <form action="/settings/save_setting" method="POST" class="form-inline">
                <div class="modal-body">';
                    foreach ($projects as $project) {
                        $checked = '';
                        if (isset($projects_selected) && count($projects_selected) > 0) {
                            if (in_array($project['name'], array_keys($projects_selected))) {
                                $checked = 'checked';
                            }
                        }
                        
                        echo '
                            <div class="form-check">
                              <label class="form-check-label">' . $project['description'] . '</label>&nbsp;
                              <input type="checkbox" name="projects_selected['. $project['name'] .']" ' . $checked . ' class="form-check-input" value="1">
                            </div>';
                    }

                    echo '
                        <div class="form-group filter_selection">
                            <label>Year</label>
                            <select name="year" class="form-control">';
                                foreach ($three_year as $value) {
                                    echo '<option value="' . $value . '" '. ($year == $value ? 'selected' : '') .'>' . $value . '</option>';
                                }

                            echo '
                            </select>
                        </div>
                        <div class="form-check">
                          <label class="form-check-label">Show Milestone that contain Story Points</label>&nbsp;
                          <input type="checkbox" name="story_points" ' . ($story_points == 1 ? 'checked' : '') . ' class="form-check-input" value="1">
                        </div>
                        <div class="form-check">
                          <label class="form-check-label">Link By Project</label>&nbsp;
                          <input type="checkbox" name="link" ' . ($link == 1 ? 'checked' : '') . ' class="form-check-input" value="1">
                        </div>
                        <input type="hidden" name="model_name" value="User">
                        <input type="hidden" name="setting_key" value="delivery_timeline">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                    </form>
            </div>
        </div>
    </div>
</div>';
    
    
echo '
    <div id="chart_div" class="full-height"></div>
</div>';
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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

    google.charts.load('current', {'packages':['gantt']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart()
    {
        var otherData = new google.visualization.DataTable();
        otherData.addColumn('string', 'Task ID');
        otherData.addColumn('string', 'Task Name');
        otherData.addColumn('string', 'Resource');
        otherData.addColumn('date', 'Start');
        otherData.addColumn('date', 'End');
        otherData.addColumn('number', 'Duration');
        otherData.addColumn('number', 'Percent Complete');
        otherData.addColumn('string', 'Dependencies');
        
        otherData.addRows(<?php echo '[' . PHP_EOL . implode(',' . PHP_EOL, $rows) . PHP_EOL . ']'; ?>);

        var options = {
            gantt: {
                barHeight: 35,
                labelMaxWidth: 500,
                labelStyle: {
                    fontName: 'Arial',
                    fontSize: 14,
                    color: '#757575'
                },
            }
        };
        var chart = new google.visualization.Gantt(document.getElementById('chart_div'));
        chart.draw(otherData, options);
    }
</script>
