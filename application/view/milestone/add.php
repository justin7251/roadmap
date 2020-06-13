<?php
/**
* /view/milestone/add.php
*
* PHP Version 5
*/

/**
* Allow to add a new milestone
*
* @category View
* @package Milestone
* @subpackage Controller
* @author Justin Leung <myjustinleung19@hotmail.com>
* @copyright 2020
* @version Release: 1.0
*/
echo '
<div class="container margin-top-10">
    <div class="navbar">
        <h3 class="home_title"><strong>' . ucwords(str_replace('-', ' ', Session::get('current_project_description'))) . '</strong></h3>';
    echo $form->right_menu($this->projects, $this->current_project_id, 'job');
echo '
    </div>
    <div class="form-container">
        <h2>Add Milestone</h2>
        <form action="/milestone/add" method="POST">';
        
echo $form->input('Milestone Name', 'text', array('field_class' => 'required', 'name' => 'name', 'required' => true));
echo '
    <div class="form-group">
        <label for="start_date">Start Date</label>
        <div class="input-group datepicker date" id="datetimepicker1">
            <input name="start_date" value="" type="text" class="form-control"/>
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
    </div>
    <div class="form-group">
        <label for="end_date">End Date</label>
        <div class="input-group datepicker date" id="datetimepicker1">
            <input name="end_date" value="" type="text" class="form-control"/>
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
    </div>
    <div class="form-group">
        <label for="goal">Milestone Goal</label>
        <textarea class="form-control wysiwyg" rows="3" name="goal"></textarea>
    </div>';

echo $form->input('', 'hidden', array('name' => 'project_id', 'value' => Session::get('current_project_id'), 'readonly' => true));

echo '
            <button type="submit" style="margin-bottom:50px;" name="submit_add_milestone" class="btn btn-primary pull-right">Submit</button>
        </form>
    </div>
</div>';
