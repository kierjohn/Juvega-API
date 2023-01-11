<?php

namespace Acme\Common;

use Acme\Common\Constants as Constants;
use Acme\Common\DataResults as DataResults;
use \Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;



trait CommonFunction
{

    public function AuthenticationError($request)
    {
        if ($request->type == Constants::JSON) {
            $result = new DataResult();
            $result->error = true;
            $result->tags = Constants::ERROR_AUTHENTICATION_EXPIRED;
            $result->message = Constants::ERROR_AUTHENTICATION;

            return json_encode($result);
        } else {
            return view(Constants::ERROR_PAGE);
        }
    }

    public function DateFormat($date)
    {
        return date(Constants::LIST_DATE_FORMAT, strtotime($date));
    }

    public function DateTimeFormat($date)
    {
        return date(Constants::LIST_DATE_TIME_FORMAT, strtotime($date));
    }

    public function AddWeek($date)
    {
        $date = strtotime($date);
        $newDate = strtotime("+7 day", $date);
        return date(Constants::LIST_DATE_TIME_FORMAT, $newDate);
    }

    function AddMonth($date)
    {
        $current_date = strtotime($date);
        $day = date('d', $current_date);

        $ndate = strtotime('last day of this month', $current_date);
        $last_date = date('d', $ndate);
        $cdate = $ndate;
        $ndate = $ndate + 86400;

        if ($last_date == $day) {
            $lastDay = strtotime('last day of this month', $ndate);
            $stringLastDay = date('d', $lastDay);

            $newDate = strtotime('+ ' . $stringLastDay . ' day', $cdate);
        } else {
            //todo
            $newDate = strtotime("+1 month", $current_date);
        }

        return date(Constants::LIST_DATE_TIME_FORMAT, $newDate);
    }

    public function AddYear($date)
    {
        $date = strtotime($date);
        $newDate = strtotime("+12 month", $date);
        return date(Constants::LIST_DATE_TIME_FORMAT, $newDate);
    }



    public function convertToFile($request, $imageName)
    {
        file_put_contents(public_path('images') . "/" . $imageName, base64_decode($request->imagescripts));
    }

    public function proccessErrorMessage($errors)
    {
        $message = null;
        $errors = (array) $errors;
        $errors = $errors[array_keys($errors)[Constants::FIRST_INDEX]];
        foreach ($errors as $key => $value) {
            // $message .= Constants::LINE_BREAK.$value[Constants::FIRST_INDEX];
            $message .= ' ' . $value[Constants::FIRST_INDEX];
        }

        return $message;
    }

    public function formatDouble($number)
    {
        return number_format($number, 2, '.', ',');
    }

    public function objectToArray($param)
    {
        return json_decode(json_encode($param), true);
    }


    public function negative($number)
    {
        return (0 - $number);
    }

    public function toAbsolute($number)
    {
        return abs($number);
    }

    public function StringPad($str, $len, $char, $format)
    {
        return str_pad($str, $len, $char, $format);
    }


    public function RequestError($e)
    {
        $result = new DataResult;

        $result->message = $e->getMessage();
        $result->error = true;
        $result->errorCodes = [500];

        return $result;
    }

    public function LogError($channel, \Exception $e)
    {
        Log::channel($channel)
            ->info(
                "\nMessage: " . $e->getMessage() .
                    "\nCode: " . $e->getCode() .
                    "\nFile: " . $e->getFile() .
                    "\nLine: " . $e->getLine() .
                    "\nTrace: " . json_encode($e->getTrace())
            );
    }

    public function UploadFile($location, $path, $name)
    {

        $directory = public_path() . "/uploads/" . $location;

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $file_location = $directory . "/" . $name;

        move_uploaded_file($path, $file_location);

        return $file_location;
    }

    public function SendNotification($devices, $notification,  $data)
    {
        if (count($devices) == 0) {
            return false;
        }

        $SERVER_API_KEY = 'AAAAJyQPQOY:APA91bFXyfk-zy5o5e6gYLWma2Ep7EzEbefUhJwUZSVaCjWQZsSK9Jw1IwiA5rqwJbLYToPeSzXhsivTQ8ZRNsge6djdcrbkgOK85gF6rGWyWr8VUJfqQ9DAy3PmnA6ahW_wPGsTQTY6';

        $data = [
            "registration_ids" => $devices,
            "notification" => $notification,
            "data" => $data
        ];

        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        return $response;
    }

    public function encrypt($string)
    {
        $encrypted = Crypt::encryptString($string);
        return $encrypted;
    }

    public function decrypt($string)
    {
        try {
            $decrypted = Crypt::decryptString($string);
        } catch (DecryptException $e) {
            //
        }
        return $decrypted;
    }
}
