<?php
 /**
  * szMail
  * 
  * LICENSE
  *
  * This source file is subject to the new BSD license that is bundled with 
  * this package in the file LICENSE.txt.
  * It is also available through the world-wide-web at this URL:
  * http://szlab.de/license/new-bsd
  * If you did not receive a copy of the license and are unable to obtain it 
  * through the world-wide-web, please send an email to license@szlab.de so 
  * we can send you a copy immediately.
  * 
  * 
  * @copyright  Copyright (c) 2010 - $currentYear$ Otto von Wesendonk, Eric Barmeyer (http://szlab.de)
  * @version    $Id$
  * @license    http://szlab.de/license/new-bsd     New BSD License
  */

class Zend_View_Helper_FormatTime extends Zend_View_Helper_Abstract
{
    function formatTime($time)
    {
        $date = new Zend_Date(strtotime($time));
        $oneWeekBefore = new Zend_Date();
        $oneWeekBefore->subDay(7);
        //$date->addDay(6);
        if($date->isToday()) // Today?
        {
            return $date->toString("H:m");
        }
        else if($date->isYesterday())// Yesterday?
        {
            return "Gestern, " . $date->toString("H:m");
        }
        else if($oneWeekBefore->isEarlier($date)) // This week?
        {
            return $date->get(Zend_Date::WEEKDAY) . $date->toString(", H:m");
        }
        else
        {
            return $date->toString("d. ") . $date->get(Zend_Date::MONTH_NAME) . $date->toString(" H:m");
        }
    }

}