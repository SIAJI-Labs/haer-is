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

/**
 * Date Format
 * 
 */
function dateFormat($rawDate, $type = 'days'){
    $date = date("Y-m-d H:i:s", strtotime($rawDate));
    $result = '';

    switch($type){
        case 'days':
            $result = date('l', strtotime($date));
            switch($result){
                case 'Monday':
                    $result = 'Senin';
                    break;
                case 'Tuesday':
                    $result = 'Selasa';
                    break;
                case 'Wednesday':
                    $result = 'Rabu';
                    break;
                case 'Thursday':
                    $result = 'Kamis';
                    break;
                case 'Friday':
                    $result = "Jum'at";
                    break;
                case 'Saturday':
                    $result = 'Sabtu';
                    break;
                case 'Sunday':
                    $result = 'Minggu';
                    break;
            }
            break;
        case 'months':
            $result = date('F', strtotime($date));
            switch($result){
                case 'January':
                    $result = 'Januari';
                    break;
                case 'February':
                    $result = 'Februari';
                    break;
                case 'March':
                    $result = 'Maret';
                    break;
                case 'April':
                    $result = 'April';
                    break;
                case 'May':
                    $result = 'Mei';
                    break;
                case 'June':
                    $result = 'Juni';
                    break;
                case 'July':
                    $result = 'Juli';
                    break;
                case 'August':
                    $result = 'Agustus';
                    break;
                case 'September':
                    $result = 'September';
                    break;
                case 'October':
                    $result = 'Oktober';
                    break;
                case 'November':
                    $result = 'November';
                    break;
                case 'December':
                    $result = 'Desember';
                    break;
            }
            break;
    }
    return $result;
}