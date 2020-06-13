<?php
class Form
{
    /**
    *
    */
    public function right_menu($projects, $current_project_id, $highlight = null)
    {
        echo '
        <!-- navigation -->
        <div class="navigation">';
        
        echo '
                <ul class="pull-right">
                    <li><a '. ($highlight == 'home' ? ' class="pg_select"': '') .' href="/home/">Delivery Timeline</a><span class="divider">|</span></li>
                    <li><a '. ($highlight == 'roadmap' ? ' class="pg_select"': '') .' href="/home/roadmap/">Product Roadmap</a><span class="divider">|</span></li>
                    <li><a '. ($highlight == 'job' ? ' class="pg_select"': '') .' href="/job/">Epics</a><span class="divider">|</span></li>';
                    
        if (Access::get_permission() == ('admin' || 'limited')) {
            //<li><a '. ($highlight == 'code_base' ? ' class="pg_select"': '') .' href="/code_base_ticket/">CodebaseHQ Tickets</a><span class="divider">|</span></li>
            echo '
                    <li><a '. ($highlight == 'milestone' ? ' class="pg_select"': '') .' href="/milestone/">Milestones</a><span class="divider">|</span></li>';
        }

        if (isset($projects)) {
            foreach($projects as $project) {
                if ($project['id'] == $current_project_id) {
                    $current_project = $project['description'];
                } else {
                    $other_projects[$project['id']] = '
                            <li><a href="/project/related_project/' . $project['id'] . '">' . $project['description'] . '</a></li>';
                }
            }
            echo '
                    <li class="dropdown user_dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="display:block">' . $current_project . '
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">' . implode($other_projects) . '</ul>
                    </li>';
        }
        echo '<span class="divider">|</span>';
        
        echo '
                    <li class="dropdown user_dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">' . Session::get('user.first_name') . ' ' . Session::get('user.last_name') . '
                            <span class="glyphicon glyphicon-user"></span>
                            <span class="caret"></span>
                      </a>';

        echo '
                      <ul class="dropdown-menu pull-right">
                            <li class="user_dropdown_right"><a href="/user/index"><i class="glyphicon glyphicon-cog pull-right"></i>Account Settings</a></li>
                            <li class="user_dropdown_right"><a href="/quality_assurance/statistics"><i class="glyphicon glyphicon-signal pull-right"></i>QA Status</a></li>';
                        
        if (Access::get_permission() == 'admin') {
            echo '
                            <li class="user_dropdown_right"><a href="/user/manage"><i class="glyphicon glyphicon-th pull-right"></i>User Management</a></li>';
        }
        if (Access::get_automation_permission() == 'automation_member') {
            echo '
                <li class="user_dropdown_right"><a href="/automation/"><i class="glyphicon glyphicon-tasks pull-right"></i>Automation</a></li>';
        }
        
        echo '
                            <li class="user_dropdown_right"><a href="/user/logout"><i class="glyphicon glyphicon-log-out pull-right"></i>Sign Out</a></li>
                        </ul>
                    </li>
                </ul> 
            </div>';
        //Session Timeout - 1 hour
        echo '<script>
            setTimeout(function(){
              window.location.href = "/user/logout";
            }, 3600000);
        </script>';
    }
    
    /**
    *
    * @param string $current_page
    */
    public function breadcrumb($current_page)
    {
        echo '
        <div>
            <hr class="hr-primary" />
            <ol class="breadcrumb">
                <a href="/automation"><button class="btn btn-primary"><i class="glyphicon glyphicon-tasks"></i> Automation</button></a>
                <li><a class="margin-left-5 breadcrumb-item" href="/automation">Index</a></li>
                <li class="breadcrumb-item active">' . $current_page . '</li>
            </ol>
        </div>';
    }
    
    /**
    *
    */
    public function progress_bar()
    {
        echo '
            <div class="row">
                <span class="">
                    <span class="text-success">Completed 3%</span>
                </span>
                <span class="">
                    <span class="text-warning">Omitted 20%</span>
                </span>
                <span class="">
                    <span class="text-danger">Outstanding 40%</span>
                </span>
            </div>
            <div class="progress">
              <div title="Completed 3%" class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" style="width:3%">
              </div>
              <div title="Omitted 40%" class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" style="width:20%">
                Omitted 20%
              </div>
              <div title="Outstanding 40%" class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" style="width:40%">
                Outstanding 40%
              </div>
            </div>
            ';
    }
    
    /**
    *
    * @return string
    */
    public function date_time_format($date_time)
    {
        if ($date_time == '0000-00-00 00:00:00') {
            return 'N/A';
        }
        $format = 'd/m/Y H:i';
        $date_time = date($format, strtotime($date_time));
        return $date_time;
    }
    
    /**
    *   Function create automation menu
    *
    * @return void
    */
    public function automation_menu()
    {
        echo '<div class="container">';
            echo '
            <nav class="navbar navbar-blue" role="navigation">
                  <ul class="nav navbar-nav">
                    <li><a href="#">Planning</a></li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">Current Progress <b class="caret"></b></a>
                      <ul class="dropdown-menu">
                        <li><a href="#">Current Focus</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Document link</a></li>
                      </ul>
                    </li>
                  </ul>
            </nav>';
        echo '</div>';
    }
    
    /**
    *
    * @return string
    */
    public function message($message, $element = array())
    {
        return '<div ' . (isset($element['id']) ? 'id="' . $element['id'] . '"' : '') . ' class="alert ' . ( isset($element['class']) ? $element['class'] : '') .  '">'
                . $message . 
               '</div>';
    }
    
    /**
    *
    * @return string
    */
    public function strip_text($text, $limit, $url = '')
    {
        $description = strip_tags($text);

        if (strlen($description) > $limit) {
            // truncate description
            $stringCut = substr($description, 0, $limit);
            // make sure it ends in a word so assassinate doesn't become ass...
            $description = substr($stringCut, 0, strrpos($stringCut, ' ')).'... '. ($url ? $url: ''); 
        }
        return $description;
    }
    
    /**
    *
    * @return string
    */
    public function button($tag = array(), $icon_class = '', $text = '')
    {
        return '<a ' . (isset($tag['id'])? 'id="' . $tag['id'] .  '"' : '') .
            (isset($tag['class'])? 'class="' . $tag['class'] .  '"' : '') .
            (isset($tag['title'])? 'title="' . $tag['title'] .  '"' : '') .
            (isset($tag['target'])? 'target="_blank"' : '') .
            (isset($tag['url'])? 'href="' . URL . $tag['url'] . '"' : '' ) . '>
            <button class="btn ' . (isset($tag['btn-class'])? $tag['btn-class'] : 'btn-default') . '" ' .
                (isset($tag['data-toggle']) ? ' data-toggle="' . $tag['data-toggle'] . '"': '') .
                (isset($tag['data-target']) ? ' data-target="' . $tag['data-target'] . '"': '') .'>
                <i class="glyphicon ' . $icon_class  .'"></i> ' . ($text ? $text : '') . '
            </button>
            </a>';
    }
    
    /**
    *
    *
    * @return string
    */
    public function submit_button($text, $type, $options = array())
    {
        return '<button type="'. $type .'" ' .
            (isset($options['id'])? 'id="' . $options['id'] .  '"' : '') .
            (isset($options['name'])? 'name="' . $options['name'] .  '"' : '') . '
            class="btn '. (isset($options['class'])?  $options['class'] : '') .'">'. $text .'</button>';
    }
    
    /**
    *
    * @return string
    */
    public function bootstrap_form($label, $input, $options = array())
    {
        if ($input) {
            return '<div class="form-group clearfix ' . (isset($options['class']) ? $options['class'] : '')  . '" ' . (isset($options['id']) ? ' id="' . $options['id'] . '"' : '') . '>
                    <label class="col-md-3">' . $label . '</label>
                    <div class="col-md-9">' . $input . '</div>
                </div>';
        }
    }
    
    public function bootstrap_view_form($label, $input, $options = array())
    {
        if ($input) {
            return '<div class="form-group ' . (isset($options['class']) ? $options['class'] : '')  . '" ' . (isset($options['id']) ? ' id="' . $options['id'] . '"' : '') . '>
                    <label >' . $label . '</label>
                    <div>' . $input . '</div>
                </div>';
        }
    }
    
    
    public function input($label, $type, $options = array())
    {
        $input = '';
        if ($label) {
            $input .= '<fieldset class="form-group '. (isset($options['field_class']) ? $options['field_class'] : '') . '">
                        <label>' . $label . '</label>';
        }
        $input .= '
            <input type="'. $type .'" '. (isset($options['id']) ? ' id="' . $options['id'] . '"' : '') .' class="form-control ' . (isset($options['class']) ? $options['class'] : '') . '"
                name="'. (isset($options['name']) ? $options['name'] : '') .'" value="'. (isset($options['value']) ? $options['value'] : '') .'" '.(isset($options['readonly']) ? 'readonly' : '').
                (isset($options['required']) ? ' required' : '') . '>';
        if ($label) {
            $input .= '</fieldset>';
        }
        return $input;
    }
    
    public function select_input($label, $options = array(), $fields = array())
    {
        $input = '<fieldset class="form-group">
                <label>'. $label . '</label>
                    <select class="form-control" name="'.(isset($options['name']) ? $options['name'] : '').'">';
                    foreach($fields as $field) {
                        $input .= '<option value="' . $field .'"' .($field == (isset($options['value']) ? $options['value'] : '') ? ' selected': '') . '>' . $field . '</option>';
                    }
        $input .= '</select>
        </fieldset>';
        return $input;
    }
    
    /**
    * story point
    *
    * @return string
    */
    public function fibonacci_numbers()
    {
        return array(0,1,2,3,5,8,13,21,34,55,89,144);
    }
    
    public function futureMilestoneTable($future_milestones, $priorities)
    {
        foreach ($future_milestones as $key => $value) {
            $total_time = '0';
            if (is_array($value['jobs']) && count($value['jobs']) > 0) {
                foreach($value['jobs'] as $t) {
                    $total_time += $t['story_points'];
                }
            }
            echo '
            <div id="milestone-' . $value['id'] . '">
                <div class="panel-body">';
                    if (isset($value['actual_date'])) {
                        echo '<div class="milestone_date">
                                <h3 style="text-align:left">
                                    <span>' . $value['name']. '</span>
                                    <span class="date_tooltips glyphicon glyphicon-question-sign" title="'. date("dS F Y ", strtotime($value['start_date'])) . ' -  ' . date("dS F Y ", strtotime($value['actual_date'])) . '"></span>
                                    <span class="story_points_holder" id="story_points_' . $value['id'] . '" style="float:right">
                                    <span class="total_ticket_time">' .
                                        $total_time . '</span> / <span class="total_story_points">' . $value['story_points'] . '</span>
                                    </span>
                                </h3>
                            </div>';
                    }
                        echo '
                    <table class="roadmap_table table">
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
                        <tbody id="drag_drop_parent_' . $value['id'] . '" ' . (!is_array($value['jobs']) ? 'class="connectedSortable sortable_drag"' : 'class="connectedSortable"') . '>';
                        
                            foreach($value['jobs'] as $job) {
                                echo '
                            <tr id="drag_drop_item_' . $job['id'] . '" data-milestone="' . $value['id'] . '" style="display: table-row;">
                                <td class="name padding-top-5"><a href="/job/view/'. $job['id'] .'" target="_blank"><span class="glyphicon btn btn-xs ' . $priorities[$job['priority']] . '" aria-hidden="true"></span></a> ' . $job['name'] . '</td>
                                <td class="confidence_level padding-top-5">' . $job['confidence_level'] . '</td>
                                <td class="story_points padding-top-5">' . $job['story_points'] . '</td>
                                <td class="status padding-top-5">' . $job['status'] . '</td>
                            </tr>';
                            }
                        } else {
                            echo '
                            <tbody id="drag_drop_parent_' . $value['id'] . '" class="connectedSortable ui-sortable">
                                <tr class="empty_tr">
                                    <td class="name"></td>
                                    <td class="confidence_level"></td>
                                    <td class="story_points"></td>
                                    <td class="status"></td>
                                </tr>
                            </tbody>';
                        }
                    echo '
                        </tbody>
                    </table>
                </div>
            </div>';
        }
    }

}
