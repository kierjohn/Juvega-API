<?php
namespace Acme\Common;

class Constants
{
    #GENERAL
    const LIMIT = 20;
    const ALL = 'All';
    const SYMBOL_ALL = '*';
    const JSON = "json";
    const ID = "id";
    const USER_INFO_ID = "user_info_id";
    const ADJUST_AMOUNT = "adjust-amount";
    const SECRET = 'secret';
    const EMPTY = '';
    const ACTIVE = 1;
    const FIRST_INDEX = 0;
    const LINE_BREAK = '<br>';
    const PASSWORD_CONFIRMATION = 'password_confirmation';

    const _TRUE = 1;
    const _FALSE = 0;
    const IS_DELETED = 0;

    #GENERAL ERROR
    const ERROR_AUTHENTICATION = "Authentication Expired.";
    const LOST_CONNECTION = "Connection Failed.";

    #Pagination
    const PAGE_INDEX = "page";
    const PAGE_SIZE = "PageSize";
    const KEYWORD = "Search";
    const SORT_ORDER = "SortOrder";
    const SORT_BY = "SortBY";


    #FORMAT
    const INPUT_DATE_FORMAT     = 'Y-m-d';
    const OUTPUT_DATE_FORMAT    = 'F d,Y';
    const ROW_DATE_TIME_FORMAT = 'Y-m-d H:i:s';
    const LIST_DATE_FORMAT = "m/d/Y";
    const LIST_DATE_TIME_FORMAT = "m/d/Y H:i:s";
    const ANDROID_DATETIME_FORMAT = 'yyyy-MM-dd hh:mm:ss';
    const ANDROID_DATE_FORMAT = 'yyyy-MM-dd';

    #DEFAULT VALUES
    const DEFAULT_SORT_ORDER = "DESC" ;
    const DEFAULT_SORT_BY = "id";

    const DESC = "DESC";
    const ASC = "ASC";

    #TIME
    const ONE_DAY = 86399;


    const OUTBOUND = 1;
    const INBOUND = 2;

}

?>
