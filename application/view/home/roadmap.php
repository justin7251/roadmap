<?php
 echo '
<div class="main_container">
    <div class="navbar">
        <h3 class="home_title"><strong>' . ucwords(str_replace('-', ' ', Session::get('current_project_description'))) . '</strong> Product Road Map</h3>';
echo $form->right_menu($this->projects, $this->current_project_id, 'roadmap');
echo '
    </div>';
     echo '
        <div class="button_container pull-right">';
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
                    <h4 class="modal-title">Configure RoadMap Setting</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <form action="/settings/save_setting/1" method="POST" class="form-inline">
                <div class="modal-body">';

                    foreach ($configure_milestone as $value) {
                    echo '
                        <div class="form-check">
                          <label class="form-check-label">' . $value['name'] . '</label>&nbsp;
                          <input type="checkbox" name="Milestone['. $value['id'] .']" ' . (!in_array($value['id'], $unchecked_milestone) ? 'checked' : '' ) . ' class="form-check-input" value="1">
                        </div>';
                    }
                echo '
                    <input type="hidden" name="model_name" value="User">
                    <input type="hidden" name="setting_key" value="roadmap.'. Session::get('current_project_id') .'">
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
    <div id="top_container">
    </div>
    <div class="tabbable row justify-content-md-center">';
    
if ($milestones) {
    $i = 0;
    $count = count($milestones);
    foreach ($milestones as $key => $value) {
        // calculate the total story point per milestone version
        $total_time = 0;

        // if (is_array($value['jobs']) && count($value['jobs']) > 0) {
        //     foreach($value['jobs'] as $t) {
        //         $total_time += $t['story_points'];
        //     }
        // }
        
        $panel_css = '';
        if (isset($value['story_points']) && $value['id'] != 999) {
            if ($total_time > $value['story_points']) {
                $panel_css .= 'border: 2px solid #DE4511; background: #F2DEDE';
            } else {
                $panel_css .= 'border: 2px solid #A5CB18; background: #FFF';
            }
        } else {
            $panel_css .= 'border: 2px solid ';
        }
        
        $class ='';
        if ($count == 3) {
            $class = 'col-md-4';
        } elseif ($count == 2) {
            $class = 'col-md-6';
        } else {
            $class = 'col-md-12';
        }
        $div_name = ($value['id'] == 999 ? 'plan_of_intent' : ($i == 0 ? 'plan_of_commitment' : 'plan_of_record'));
        echo '
        <div class="panel-group ' . $class . ' padding-5" id="accordion-' . $value['id'] . '" >
            <input type="hidden" class="hidden_milestone_name" id="hidden-name-' . $value['id'] . '" value="' . $value['name'] . '" />
            <div class="panel panel-default" style="' . $panel_css . '">
                <div class="panel-heading ' . $div_name . '">
                    <h4 class="pull-right margin-top-5 story_points_holder" id="story_points_' . $value['id'] . '">Total Time Available <span class="total_ticket_time">' . $total_time . '</span>'
            . ($value['id'] != 999 && $i != 2 ? ' / <span class="total_story_points">10</span>' : '' ) . ' </h4>

                    <h3 class="panel-title">';
                    
        if ($value['id'] == 999) {
            echo $value['name'] . '<br><strong>All Future Identified Work, by Milestone</strong>';
        } else {
            echo ($i == 0 ? 'Plan of Commitment ' : 'Plan of Record ') . '<br><strong>' . $value['name'] . '</strong>';
        }
        echo '</h3>
                </div>';
                
        if ($value['id'] != 999) {
            echo '
                <div id="milestone-' . $value['id'] . '">
                    <div class="panel-body">';
                    
            if (isset($value['end_date'])) {
                echo '
                        <div class="milestone_date"><h3>' . date("dS F Y ", strtotime($value['start_date'])) . ' - '. date("dS F Y ", strtotime($value['end_date'])) . '</h3></div>';
            }
            echo '
                        <table id="table-' . $div_name . '" class="roadmap_table table">
                            <thead>
                                <tr>
                                    <th class="name">Epic</th>
                                    <th class="confidence_level">Confidence Level</th>
                                    <th class="story_points">Estimate</th>
                                </tr>
                            </thead>';
                            
            //check jobs
            if (is_array($value['jobs']) && count($value['jobs']) > 0) {
                echo '
                            <tbody id="drag_drop_parent_' . $value['id'] . '" ' . ($count > 1 ? (!is_array($value['jobs']) ? 'class="connectedSortable sortable_drag"' : 'class="connectedSortable"') : '') . '>';
                                    
                foreach($value['jobs'] as $job) {
                    echo '
                                <tr id="drag_drop_item_' . $job['id'] . '" data-milestone="' . $value['id'] . '" style="display: table-row;">
                                    <td class="name padding-top-5"><a href="/job/view/'. $job['id'] .'" target="_blank"><span class="glyphicon btn btn-xs ' . $priorities[$job['priority']] . '" aria-hidden="true"></span></a> ' . $job['name'] . '</td>
                                    <td class="confidence_level padding-top-5">' . $job['confidence_level'] . '</td>
                                    <td class="story_points padding-top-5">1</td>
                
                                </tr>';
                }
                echo '
                            </tbody>';
            } else {
                echo '
                            <tbody id="drag_drop_parent_' . $value['id'] . '" class="connectedSortable ui-sortable">
                                <tr class="empty_tr">
                                    <td class="name"></td>
                                    <td class="story_points"></td>
                                    <td class="status"></td>
                                </tr>
                            </tbody>';
            }
            echo '
                        </table>
                    </div>
                </div>';
        } else {
            if (isset($future_milestones[0]['id'])) {
                $form->futureMilestoneTable($future_milestones, $priorities);
            }
            echo '
                <div id="milestone-' . $value['id'] . '">
                    <div class="panel-body">
                    <div class="milestone_date"><h3>All Other Epics</h3></div>
                        <table id="table-' . $div_name . '" class="roadmap_table table">
                            <thead>
                                <tr>
                                    <th class="name">Epic</th>
                                    <th class="confidence_level">Confidence Level</th>
                                    <th class="story_points">Estimate</th>
                                    <th class="status">Status</th>
                                </tr>
                            </thead>';
                        
            //check jobs
            if (is_array($value['jobs']) && count($value['jobs']) > 0) {
                echo '
                            <tbody id="drag_drop_parent_' . $value['id'] . '" ' . ($count > 1 ? (!is_array($value['jobs']) ? 'class="connectedSortable sortable_drag"' : 'class="connectedSortable"') : '') . '>';
                                    
                foreach($value['jobs'] as $job) {
                    echo '
                                <tr id="drag_drop_item_' . $job['id'] . '" data-milestone="' . $value['id'] . '" style="display: table-row;">
                                    <td class="name padding-top-5"><a href="/job/view/'. $job['id'] .'" target="_blank"><span class="glyphicon btn btn-xs ' . $priorities[$job['priority']] . '" aria-hidden="true"></span></a> ' . $job['name'] . '</td>
                                    <td class="confidence_level padding-top-5">' . $job['confidence_level'] . '</td>
                                    <td class="story_points padding-top-5">' . $job['story_points'] . '</td>
                                    <td class="status padding-top-5">' . $job['status'] . '</td>
                                </tr>';
                }
                echo '
                            </tbody>';
            } else {
                echo '
                            <tbody id="drag_drop_parent_' . $value['id'] . '" class="connectedSortable ui-sortable">
                                <tr class="empty_tr">
                                    <td class="name"></td>
                                    <td class="story_points"></td>
                                    <td class="status"></td>
                                </tr>
                            </tbody>';
            }
            echo '
                        </table>
                    </div>
                </div>';
        }
        echo '
            </div>
        </div>';
        
        $i++;
    }
}
echo '
    </div>
</div>';
?>
<script>
    <?php if (Access::get_permission() != 'admin') { ?>
            $(".connectedSortable").removeClass("connectedSortable");
    <?php } ?>
</script>
