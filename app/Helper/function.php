<?php

/**
 * Generate Avatar
 * 
 * @param $name = String
 * @param $type = ['male', 'female', 'human', 'identicon', 'initials', 'bottts', 'avataaars', 'jdenticon', 'gridy', 'micah']
 */
function getAvatar($name, $type = 'initials')
{
    $avatar = "https://avatars.dicebear.com/api/".$type."/".$name.".svg";
    return $avatar;
}

/**
 * Whatsapp Format
 * 
 */
function whatsappFormat($attendanceData, $type = 'check-in')
{
    $user = $attendanceData->user;
    if($type == 'check-in'){
        $date = date("d/m/Y", strtotime($attendanceData->checkin_time));
        $type = 'Check-In';
    } else {
        $date = date("d/m/Y", strtotime($attendanceData->checkout_time));
        $type = 'Check-Out';
    }

    // Create Template
    $template = "*$type* {$user->name} | {$attendanceData->date} | {$date}";
    $template .= "%0AActivities";
    foreach($attendanceData->attendanceTask as $attendanceTask){
        if(strtolower($type) == 'check-in'){
            $template .= "%0A".($attendanceTask->start > 0 ? "" : "[".$attendanceTask->start."%] {$attendanceTask->task->name}");
        } else {
            $template .= "%0A".($attendanceTask->end > 0 ? "" : "[".$attendanceTask->end."%] {$attendanceTask->task->name}");
        }
    }
    $template .= "%0ALocation: YEWI";

    return $template;
}