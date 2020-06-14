<?php

class Home_Model extends Model
{
    public function cmp($a, $b)
    {
        return strcmp($a["fruit"], $b["fruit"]);
    }
    
    public function order_by_milestone($milestones, $tickets)
    {
        $count = 0;
        $milestone_ticket = array();

        foreach ($milestones as $milestone) {
            $count += 1;
            foreach ($tickets as $ticket) {
                if ($ticket['milestone_name'] == $milestone['name']){
                    if ( $count < 2) {
                        $ticket['title'] = 'Plan Of Record ' . $milestone['name'];
                        $milestone_ticket[0][] = $ticket;
                    } elseif ($count == 2) {
                        $ticket['title'] = 'Plan Of Commitment ' . $milestone['name'];
                        $milestone_ticket[1][] = $ticket;
                    } else {
                        $ticket['title'] = 'Plan Of Intent';
                        $milestone_ticket[2][] = $ticket;
                    }
                } elseif ($ticket['milestone_name'] == null) {
                    $milestone_ticket[2][] = $ticket;
                }
            }
        }
        return $milestone_ticket;
    }
}
?>