<?php
echo '
<div class="container margin-top-10 dom">
    <div class="navbar">
        <h3 class="home_title margin-bottom-10"><strong>Automation</strong></h3>';
            echo $form->right_menu($this->projects, $this->current_project_id);
echo '
    </div>';
echo $form->automation_menu();    

echo '
<div class="row">';
    echo '
        <div class="button_container pull-right">';
        //button
        echo $form->button(
            array(
                'id' => '',
                'class' => 'no_decoration',
                'btn-class' => 'btn-primary',
                'title' => 'document links',
                'url' => 'automation/document'
            ),
            'glyphicon-folder-open',
            'Document Links'
        );
        
        echo $form->button(
            array(
                'id' => '',
                'class' => 'no_decoration',
                'btn-class' => 'btn-success',
                'title' => 'Assign user',
                'url' => 'automation/add'
            ),
            'glyphicon-plus',
            'New Class'
        );
        
        echo $form->button(
            array(
                'id' => '',
                'class' => 'no_decoration',
                'btn-class' => 'btn-primary',
                'title' => 'Assign user',
                'url' => 'automation/measure'
            ),
            'glyphicon-list-alt',
            'Measure'
        );
        
        echo '<div class="dropdown pull-right margin-left-5">
              <button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown">Autoamation Import
              <span class="caret"></span></button>
              <ul class="dropdown-menu no_decoration">
                <li><a href="/automation/jenkins_import" target="_blank">Jenkins Import</a></li>
                <li><a href="/automation/qtest_import" target="_blank">TestRail Import</a></li>
                <li><a href="/automation/progress_status_import" target="_blank">Progress Status Import</a></li>
              </ul>
            </div>';
    echo '</div>';

    echo '
    <div>
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#current" data-toggle="tab">Current Working On</a>
            </li>
            <li>
                <a href="#future" data-toggle="tab">Future Focus</a>
            </li>
        </ul>
        <div class="tab-content clearfix" style="margin-bottom:20px">
            <div class="tab-pane active" id="current">';
            if (isset($current) && count($current) > 0) {
                foreach ($current['working'] as $key => $value) {
                    echo '<div class="col-md-3">
                            <div class="content margin-10 padding-10" style="border:solid 1px">
                                <div class="icon-holder">
                                    <h4>
                                        <span class="glyphicon glyphicon-user margin-right-5"></span>' .$key. '
                                    </h4>
                                </div>
                                <div>';
                                foreach ($value as $v) {
                                    echo 'Working on <span class="text-primary">' . $v . '</span>';
                                    // echo $form->progress_bar();
                                }
                    echo '
                                </div>
                            </div>
                        </div>';
                }
            }
            if (isset($current['waiting_sign_off']) && count($current['waiting_sign_off']) > 0) {
                    echo '<div class="col-md-3">
                            <div class="content margin-10 padding-10" style="border:solid 1px">
                                <div class="icon-holder">
                                    <h4 class="text-danger">
                                    <span class="glyphicon glyphicon-time"></span>
                                        Waiting Sign Off
                                    </h4>
                                </div>';
                            echo '
                                <div>';
                            foreach ($current['waiting_sign_off'] as $value) {
                                echo $value . '<br/>';
                            }
                    echo '
                                </div>';
                        echo '</div>
                            </div>';
            }
        echo '
            </div>
            <div class="tab-pane" id="future">
                <div class="col-md-12">';
            if (isset($current['future_focus']) && count($current['future_focus']) > 0) {
                    echo '<div class="col-md-3">
                            <div class="content margin-10 padding-10" style="border:solid 1px">
                                <div class="icon-holder">
                                    <h4 class="text-danger">
                                    <span class="glyphicon glyphicon-road"></span>
                                        Future Focus
                                    </h4>
                                </div>';
                            echo '
                                <div>';
                            foreach ($current['future_focus'] as $value) {
                                echo $value . '<br/>';
                            }
                        echo '
                                </div>';
                        echo '</div>
                            </div>';
            }
            echo '
                </div>
            </div>
        </div>
    </div>';
    
if (isset($automations)) {
    echo '
    <table class="table sortable">
        <thead>
            <tr>
                <th>Class Name</th>
                <th>Outstanding Test</th>
                <th>Omitted Test</th>
                <th>Completed Test</th>
                <th>Total QTest</th>
                <th>Assign To</th>
                <th>Status</th>
                <th class="col-md-1"></th>';
    echo '
            </tr>
        </thead>
        <tbody>';

    if (is_array($automations) && count($automations) > 0) {
        foreach($automations as $automation) {
            echo '
            <tr>
                <td><a href="/automation/view/'.$automation['id'].'">' . $automation['class_name'] . '</a></td>
                <td '. ($automation['tap_skip'] > 0 ? 'class="text-danger bg-danger"' : '') . '>' . $automation['tap_skip'] . '</td>
                <td '. ($automation['tap_todo'] > 0 ? 'class="text-warning bg-warning"' : '') . '>' . $automation['tap_todo'] . '</td>
                <td '. ($automation['tap_completed'] > 0 ? 'class="text-success bg-success"' : '') . '>' . $automation['tap_completed'] . '</td>
                <td>' . $automation['qtest_count'] . '</td>
                <td>' . $automation['assign_to'] . '</td>
                <td>' . ucwords(str_replace('_', ' ', $automation['status'])) . '</td>';
                echo '
                <td>' . $form->button(array('btn-class' => 'btn-success', 'title' => 'Edit Automation', 'url' => 'automation/edit/' . $automation['id']), 'glyphicon-edit') . '</td>';
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

</script>