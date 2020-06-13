<?php

/**
 * Class Code_Base
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class Code_Base_Model
{
    private $db;
    private $model;
    //
    public function __construct($database)
    {
        $this->db = $database;
        $this->Code_base_ticket_Model = new Code_base_ticket_Model($this->db);
        $this->Milestone_Model = new Milestone_Model($this->db);
    }
    
    /**
    * Returns an array from fetched XML
    *
    * @param array $xml XML data to turn to array
    * @return array
    */
    public function get_xml_array($xml) {
      $array = json_decode(json_encode($xml), TRUE);
      
      foreach (array_slice($array, 0) as $key => $value) {
        if (empty($value)) $array[$key] = NULL;
        elseif (is_array($value)) $array[$key] = $this->get_xml_array($value); 
      }
      return $array;
    }

    /*
    * Function that import all code base ticket or single ticket
    * Import all will only import 20 most recent ticket
    * 
    * @param int $id
    * @param string $project_name
    * @return void
    */
    public function import_code_base_ticket($project_name, $ids = array())
    {
        // wez & j are here!!!
        if (!is_array($ids) || count($ids) <= 0 || strlen($project_name) < 3) {
            return false;
        }
        // set project name for now
        //$project_name = 'development-workshop';
        // Get current project id
        $sql = "SELECT `id` FROM `project` WHERE `name` = '" . $project_name . "'";
        $project_id =  $this->Code_base_ticket_Model->raw_query($sql);
        
        //delete existing id
        $sql = "DELETE FROM `code_base_ticket` WHERE `ticket-id` IN (" . implode(', ', $ids) . ");";
        $this->Code_base_ticket_Model->raw_query($sql);
        
        foreach ($ids as $id) {
            $result = $this->get_code_base_ticket($id, $project_name, $project_id[0]['id']);
            if (isset($result['ticket-id'])) {
               //code base ticket check before save
                $this->Code_base_ticket_Model->save($result);
            }
        }
        return false;
    }
    
    /*
    * Function that import all code base ticket or single ticket
    * Import all will only import 20 most recent ticket
    * 
    * @param int $id
    * @param string $project_name
    * @return void
    */
    public function importTicketsByTag($project_name, $tag, $job_id)
    {
        if (strlen($tag) < 1) {
            return false;
        }

        $sql = "SELECT `id` FROM `project` WHERE `name` = '" . $project_name . "'";
        $project_id =  $this->Code_base_ticket_Model->raw_query($sql);
        
        if ($project_id[0]['id'] < 1) {
            return false;
        }
        $tickets = array();
        foreach ($this->getTicketsByTag($project_name, $tag) as $ticket) {
            $tickets[$ticket['ticket-id']] = array(
                'ticket-id' => $ticket['ticket-id'],
                'ticket-type' => $ticket['ticket-type'],
                'project_id' => $project_id[0]['id'],
                'summary' => htmlspecialchars($ticket['summary']),
                'code_base_milestone_id' => ($ticket['milestone-id'] ? $ticket['milestone-id'] : ''),
                'estimated-time' => (!is_array($ticket['estimated-time']) ? $ticket['estimated-time'] : ''),
                'total-time-spent' => ($ticket['total-time-spent'] ? $ticket['total-time-spent'] : ''),
                'priority' => ($ticket['priority']['name'] ? $ticket['priority']['position'] . '. '. $ticket['priority']['name'] : ''),
                'status' => ($ticket['status']['name'] ? $ticket['status']['name'] : ''),
                'assignee' => $ticket['assignee'],
                'create_at' => date("Y-m-d H:i:s")
            );
        }
        
        //delete existing id
        $sql = "DELETE FROM `code_base_ticket` WHERE `ticket-id` IN (" . implode(', ', array_keys($tickets)) . ");";
        $this->Code_base_ticket_Model->raw_query($sql);
        $this->Code_base_ticket_Model->multiple_save($tickets);
        // add mapping logic
        if (!isset($this->Mappings)) {
            $this->Mappings = new Job_Ticket_Mappings_Model($this->db);
        }
        // delete all mapping that link to that job id
        $this->Mappings->deleteAll($job_id);
        foreach ($tickets as $ticket) {
            $this->Mappings->set_mapping($job_id, $ticket['ticket-id']);
        }
        return $tickets;
    }
    
    public function get_code_base_ticket($id, $project_name, $project_id)
    {
        $url = 'https://api3.codebasehq.com/' . $project_name . '/tickets/' . $id;
        $ticket = $this->get_code_base_data($url);

        if (!isset($ticket['ticket-id']) ||$ticket['ticket-id'] != $id) {
            return false;
        }
        $result = array(
            'ticket-id' => $ticket['ticket-id'],
            'ticket-type' => $ticket['ticket-type'],
            'project_id' => $project_id,
            'summary' => htmlspecialchars($ticket['summary']),
            'code_base_milestone_id' => ($ticket['milestone-id'] ? $ticket['milestone-id'] : ''),
            'estimated-time' => (!is_array($ticket['estimated-time']) ? $ticket['estimated-time'] : ''),
            'total-time-spent' => ($ticket['total-time-spent'] ? $ticket['total-time-spent'] : ''),
            'priority' => ($ticket['priority']['name'] ? $ticket['priority']['position'] . '. '. $ticket['priority']['name'] : ''),
            'status' => ($ticket['status']['name'] ? $ticket['status']['name'] : ''),
            'assignee' => $ticket['assignee'],
            'create_at' => date("Y-m-d H:i:s")
        );
        return $result;
    }
    
    /**
    * Import Bug ability ticket from codebase
    */
    public function get_code_base_bugability($project_name, $search = 'resolution:open')
    {
        $tickets = array();
        $category_array = array(
            '1. Urgent' => 10,
            '2. High' => 5,
            '3. Normal' => 2,
            '4. Low' => 1,
            'Unclassified' => 0
        );

        // limited 10 page with 200 result
        for ($i = 1; $i <= 10; $i++) {
            $url = 'https://api3.codebasehq.com/' . $project_name . '/tickets?page='. $i .'&&query=type:Bug+' . $search;
            $data = $this->get_code_base_data($url);
            if (isset($data['ticket'])) {
                if (!array_key_exists(0, $data['ticket'])) {
                    $tickets = array($data['ticket']);
                } else {
                    foreach ($data['ticket'] as $k => $ticket) {
                        $tickets[$ticket['ticket-id']] = array(
                            'ticket-id' => $ticket['ticket-id'],
                            'ticket-type' => $ticket['ticket-type'],
                            'tags' => $ticket['tags'],
                            'category_name' => ($ticket['category']['name'] ? $ticket['category']['name'] : 'N/A'),
                            'bugability_score' => ($ticket['category']['name'] ? $category_array[$ticket['category']['name']] : 0),
                            'summary' => htmlspecialchars($ticket['summary']),
                            'code_base_milestone_id' => ($ticket['milestone-id'] ? $ticket['milestone-id'] : ''),
                            'priority' => ($ticket['priority']['name'] ? $ticket['priority']['position'] . '. '. $ticket['priority']['name'] : ''),
                            'status' => ($ticket['status']['name'] ? $ticket['status']['name'] : ''),
                            'create_at' => date("Y-m-d H:i:s")
                        );
                    }
                }
            } else {
                break;
            }
        }
        return $tickets;
    }
    
    /**
    *
    */
    public function get_milestone($project_name)
    {
        if (!isset($project_name)) {
            return false;
        }
        $url = 'https://api3.codebasehq.com/' . $project_name . '/milestones';
        $data = $this->get_code_base_data($url);
        $milestones = $data['ticketing-milestone'];

        //get project id from project id 
        $project_id = $this->Code_base_ticket_Model->raw_query("SELECT `id` FROM `project` WHERE `name` = '" . $project_name . "'");
        //loop milestone and find existing id
        foreach($milestones as $value) {
            $ids[] = $value['id'];
        }
        $existing_milestone = $this->Code_base_ticket_Model->raw_query("SELECT `id` from `milestone` WHERE `id` IN (" . implode(",", $ids) . ")");
        $existing_milestone_id = array();
        
        if (isset($existing_milestone[0]['id'])) {
            foreach ($existing_milestone as $milestone) {
                array_push($existing_milestone_id, $milestone['id']);
            }
        }
        $new_milestone = $old_milestone = array();

        foreach($milestones as $value) {
            // if ($value["status"] != 'active') {
                // continue;
            // }
            $all_milestone = array();
            $all_milestone['id'] = $value['id'];
            $all_milestone['project_id'] = $project_id[0]['id'];
            $all_milestone['name'] = $value['name'];
            $all_milestone['description'] = $value['description'];
            // $all_milestone['story_points'] = $value['estimated-time'];
            $all_milestone['start_date'] = (is_array($value['start-at']) ?  NULL : $value['start-at']);
            $all_milestone['actual_date'] = (is_array($value['deadline']) ? NULL : $value['deadline'] . ' 23:59:59');
            //milestone is active = 0 and complete or cancelled = 1
            $all_milestone['completed'] = ($value["status"] == 'completed' ? 1 : 0);
            $all_milestone['create_at'] = date("Y-m-d H:i:s");
            if (count($existing_milestone_id) > 0) {
                if (in_array($value['id'], $existing_milestone_id)) {
                    $old_milestone[] = $all_milestone;
                } else {
                    $all_milestone['active'] = ($value['status'] == 'active' ? 'Y': 'N');
                    $new_milestone[] = $all_milestone;
                }
            } else {
                $all_milestone['active'] = ($value['status'] == 'active' ? 'Y': 'N');
                $new_milestone[] = $all_milestone;
            }
        }

        if (count($old_milestone) > 0) {
            foreach ($old_milestone as $milestone) {
                $this->Milestone_Model->save($milestone);
            }
        }
        if (count($new_milestone) > 0) {
            $this->Milestone_Model->multiple_save($new_milestone);
        }
        //delete non-existant database milestones for this project
        $all_milestone_id = array();
        $all_milestone = $this->Code_base_ticket_Model->raw_query("SELECT `id` FROM `milestone` WHERE `project_id` = " . $project_id[0]['id']);
        foreach ($all_milestone as $milestone) {
            array_push($all_milestone_id, $milestone['id']);
        }
        $result = array_diff($all_milestone_id, $ids);
        
        if (isset($result) && count($result) > 0) {
            $this->Code_base_ticket_Model->raw_query("DELETE FROM `milestone` WHERE `id` IN (" . implode(",", $result) . ")");
        }
        return true;
    }
    
    /**
    * Sync ticket back to Code base
    * Update ticket milestone
    *
    * @return void
    */
    public function update_all_tickets($post_data)
    {
        if (!isset($post_data['ticket_id']) || count($post_data['ticket_id']) > 0 ) {
            return false;
        }
        $Jobs = new Job_View_Model($this->db);
        $id = array('IN' => explode(',', $post_data['ticket_id']));
        $jobs = $Jobs->get(array(), array('id' => $id), 'ORDER BY ticket_sort ASC');
        foreach($jobs as $job) {
            $this->updateJobTickets($job);
        }
    }

    public function update_job_tickets($job)
    {
        if ($job['code_base_ticket'] != NULL) {
            $ticket_ids = explode(',', $job['code_base_ticket']);
            //$tickets = $this->getTicketsByTag('my_tag');
            foreach($ticket_ids as $id) {
                $ticket = $this->get_code_base_ticket($id, $job['project_name'], $job['id']);
                
                if (!$ticket['ticket-id'] || $ticket['code_base_milestone_id'] == $job['milestone_id']) {
                    continue;
                }
                $url = 'https://api3.codebasehq.com/' . $job['project_name'] . '/tickets/' . $id .  '/notes';
                $data = '<ticket-note>
                            <content>User: ' . Session::get('user.first_name') . ' ' . Session::get('user.last_name') . ' update ticket from roadmap </content>
                            <changes>
                                <milestone-id>' . $job['milestone_id'] . '</milestone-id>
                            </changes>
                         </ticket-note>';

                $this->update_code_base_data($url, $data);
            }
        }
    }
    
    public function updateJobTickets($project_name, $job)
    {
        if ($job['code_base_tag'] != NULL && strlen($job['code_base_tag']) > 0) {
            $tickets = $this->getTicketsByTag($project_name, $job['code_base_tag']);
            foreach($tickets as $ticket) {
                if (!$ticket['ticket-id'] || $job['milestone_id'] < 1 || $ticket['milestone-id'] == $job['milestone_id']) {
                    continue;
                }
                $url = 'https://api3.codebasehq.com/' . $project_name . '/tickets/' . $ticket['ticket-id'] .  '/notes';
                $data = '<ticket-note>
                            <content>Milestone automatically updated by ' . Session::get('user.first_name') . ' ' . Session::get('user.last_name') . ', using the S2 Roadmap Application</content>
                            <changes>
                                <milestone-id>' . $job['milestone_id'] . '</milestone-id>
                            </changes>
                         </ticket-note>';
                         
                $this->update_code_base_data($url, $data);
            }
        }
    }
    
    
    public function getJobTickets($project_name, $job)
    {
        if ($job['code_base_tag'] != NULL && strlen($job['code_base_tag']) > 0) {
            $this->importTicketsByTag($project_name, $job['code_base_tag'], $job['id']);
        }
    }
    
    public function getTicketsByTag($project_name, $tag)
    {
        $tickets = array();
        //tickets/?page=4&&query=tags:
        $key = 0;
        // limited 10 page with 100 result
        for ($i = 1; $i <= 10; $i++) {
            $url = 'https://api3.codebasehq.com/' . $project_name . '/tickets/?page='. $i .'&&query=tags:"' . $tag . '"';
            $data = $this->get_code_base_data($url);
            if (isset($data['ticket'])) {
                if (!array_key_exists(0, $data['ticket'])) {
                    $tickets = array($data['ticket']);
                } else {
                    foreach ($data['ticket'] as $k => $ticket) {
                        $tickets[$key] = $ticket;
                        $key++;
                    }
                }
            } else {
                break;
            }
        }
        return $tickets;
    }
    
    
    /*
    * Function that import all code base ticket or single ticket
    * Import all will only import 20 most recent ticket
    * 
    * @param string $project_name
    * @param int $sprint
    * @return void
    */
    public function getTicketsBySprint($project_name, $sprint)
    {
        if (strlen($sprint) < 1) {
            return false;
        }
        $sprint = 'RW 3.1.1 - Sprint 3';
        $sprint = str_replace(' ', '+', $sprint);
        prr($sprint, 'after');

        $tickets = array();
        //tickets/?page=4&&query=sprints:
        $key = 0;
        // limited 10 page with 100 result
        for ($i = 1; $i <= 10; $i++) {
            $url = 'https://api3.codebasehq.com/' . $project_name . '/tickets/?page='. $i .'&&query=sprint:"' . $sprint . '"';
            prr($url);
            $data = $this->get_code_base_data($url);
            prr($data,'data');
            if (isset($data['ticket'])) {
                if (!array_key_exists(0, $data['ticket'])) {
                    $tickets = array($data['ticket']);
                } else {
                    foreach ($data['ticket'] as $k => $ticket) {
                        $tickets[$key] = $ticket;
                        $key++;
                    }
                }
            } else {
                break;
            }
        }
        
        // foreach ($this->getTicketsByTag($project_name, $tag) as $ticket) {
            // $tickets[$ticket['ticket-id']] = array(
                // 'ticket-id' => $ticket['ticket-id'],
                // 'ticket-type' => $ticket['ticket-type'],
                // 'project_id' => $project_id[0]['id'],
                // 'summary' => htmlspecialchars($ticket['summary']),
                // 'code_base_milestone_id' => ($ticket['milestone-id'] ? $ticket['milestone-id'] : ''),
                // 'estimated-time' => (!is_array($ticket['estimated-time']) ? $ticket['estimated-time'] : ''),
                // 'total-time-spent' => ($ticket['total-time-spent'] ? $ticket['total-time-spent'] : ''),
                // 'priority' => ($ticket['priority']['name'] ? $ticket['priority']['position'] . '. '. $ticket['priority']['name'] : ''),
                // 'status' => ($ticket['status']['name'] ? $ticket['status']['name'] : ''),
                // 'assignee' => $ticket['assignee'],
                // 'create_at' => date("Y-m-d H:i:s")
            // );
        // }
        prr($tickets);die;

    }

    /**
    * cURL setup function, from posting data from codebase connection
    * cURL Post
    *
    * @param string $url
    * @param int $ticket_id
    * @param int $code_base_id
    * @return void
    */
    public function update_code_base_data($url, $data)
    {
        $auth = 's2-partnership-ltd/systems-analysis-pool-87:32fc1c7f9f502e75ede1e60b960c1c0f0ccbfef8';
        $headers = array('Accept: application/xml', 'Content-type: application/xml');

        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, $auth);
        curl_setopt($ch, CURLOPT_POST,TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
        $result = curl_exec ($ch);
        curl_close ($ch);
    }
    
    /**
    * cURL setup function, from getting data from codebase connection
    * cURL Get
    *
    * @param string $url
    * @return array data
    */
    public function get_code_base_data($url = null)
    {
        $auth = 's2-partnership-ltd/systems-analysis-pool-87:32fc1c7f9f502e75ede1e60b960c1c0f0ccbfef8';
        $headers = array('Accept: application/xml', 'Content-type: application/xml');
        $out = array();
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, $auth);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);
              
        $myxml = simplexml_load_string($result);
        return $this->get_xml_array($myxml);
    }

}
