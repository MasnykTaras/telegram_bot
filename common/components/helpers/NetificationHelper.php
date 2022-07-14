<?php
namespace common\components\helpers;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class NetificationHelper
{

    public static function checkingSignature($header, $request)
    {
	if (self::createSignature($request) == self::getSignature($header)) {
            return true;
        }
        return false;
    }
    private static function createSignature($request)
    {
        $string = self::getString($request);
        if ($string) {
            return self::createHash($string);
        }
        return false;
    }
    private static function getString($request)
    {
        if ($request->notification_type == 'card_verification') {
            $string = self::createCardString($request);
        } else if ($request->notification_type == 'order_status') {
            $string = self::createOrderStatucString($request);
        } else if ($request->notification_type == 'email_verification') {
            $string = self::createEmailString($request);
        } else if ($request->notification_type == 'user_discount') {
            $string = self::createDiscountString($request);
        }
        return $string;
    }

//      email.value|email.status
    private static function createEmailString($request)
    {
        if (isset($request->email->value) && isset($request->email->status)) {
            return $request->email->value . '|' . $request->email->status;
        }
        return false;
    }

//      card.code|card.number|card.status
    private static function createCardString($request)
    {
        if (isset($request->card->code) && isset($request->card->number) && isset($request->card->status)) {
            return $request->card->code . '|' . $request->card->number . '|' . $request->card->status;
        }
        return false;
    }
//     orderHash|sell_amount|buy_amount|rate|status
    private static function createOrderStatucString($request)
    {
        if ($request->orderHash . '|' . $request->sell_amount . '|' . $request->buy_amount . '|' . $request->rate . '|' . $request->status) {
            return $request->orderHash . '|' . $request->sell_amount . '|' . $request->buy_amount . '|' . $request->rate . '|' . $request->status;
        }
        return false;
    }
//     telegramUserId|discount
    private static function createDiscountString($request)
    {
        if ($request->user->telegramUserId . '|' . $request->user->discount) {
            return $request->user->telegramUserId . '|' . $request->user->discount;
        }
        return false;
    }

    private static function createHash($string)
    {
        return hash_hmac("SHA256", $string, env('NOTIFICATION_SECRET_KEY'));
    }

    private static function getSignature($header)
    {
        if ($header->offsetExists('x-api-signature-sha256')) {
            return $header->offsetGet('x-api-signature-sha256');
        }
        return false;
    }

}
