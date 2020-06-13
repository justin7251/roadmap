<?php

class Settings_Model extends Model
{
    public $_fields = array(
        'id',
        'model_name',
        'model_id',
        'setting_key',
        'setting_value',
        'create_at',
    );
    protected $_table = 'settings';
    
    
    public function get_setting($model_name, $model_id, $setting_key)
    {
        return $this->raw_query("SELECT `id`,`setting_value` FROM `settings` WHERE `model_name` = '" . $model_name . "' AND `model_id` = " . $model_id . " AND `setting_key` = '" . $setting_key . "'");
    }
    
    public function delete_setting($model_name, $model_id, $setting_key)
    {
        $this->raw_query("DELETE FROM `settings` WHERE `model_name` = '" . $model_name . "' AND `model_id` = " . $model_id . " AND `setting_key` = '" . $setting_key . "'");
    }
}
?>