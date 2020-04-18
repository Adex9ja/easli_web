<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2/18/2020
 * Time: 10:06 PM
 */

namespace App\Model;


class RequestStatus
{
    const Declined = -1;
    const Pending = 0;
    const Approved = 1;
    const Cancelled = 2;
    const Insufficient = 3;

    public static function getReqTitle($status)
    {
        switch ($status){
            case -1:
                return "Declined";
            case 0:
                return "Pending";
            case 1:
                return "Approved";
            case 2:
                return "Cancelled";
            case 3:
                return "Insufficient Balance";
            default:
                return "";
        }
    }

    public static function getPill($status)
    {
        switch ($status){
            case -1:
                return "badge-danger";
            case 2:
                return "badge-dark";
            case 0:
            case 3:
                return "badge-warning";
            case 1:
                return "badge-success";
            default:
                return "";
        }
    }
}
