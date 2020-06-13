<?php

class Milestone_Model extends Model
{
    public $_fields = array(
        'id',
        'name',
        'description',
        'internal_patch_note',
        'external_patch_note',
        'story_points',
        'project_id',
        'active',
        'start_date',
        'actual_date',
        'completed',
        'create_at'
    );
    protected $_table = 'milestone';
    
}
?>