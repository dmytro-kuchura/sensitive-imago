<?php

namespace Modules\Ajax\Controllers;

use Core\CommonI18n;
use Core\GeoIP;
use Core\QB\DB;
use Core\Arr;
use Core\System;
use Core\Log;
use Core\Email;

class Form extends \Modules\Ajax
{

    protected $post;
    protected $files;

    function before()
    {
        parent::before();
        // Check for bans in blacklist
        $ip = GeoIP::ip();
        $ips = array();
        $ips[] = $ip;
        $ips[] = $this->ip($ip, array(0));
        $ips[] = $this->ip($ip, array(1));
        $ips[] = $this->ip($ip, array(1, 0));
        $ips[] = $this->ip($ip, array(2));
        $ips[] = $this->ip($ip, array(2, 1));
        $ips[] = $this->ip($ip, array(2, 1, 0));
        $ips[] = $this->ip($ip, array(3));
        $ips[] = $this->ip($ip, array(3, 2));
        $ips[] = $this->ip($ip, array(3, 2, 1));
        $ips[] = $this->ip($ip, array(3, 2, 1, 0));
        if (count($ips)) {
            $bans = DB::select('date')->from('blacklist')->where('status', '=', 1)->where('ip', 'IN', $ips)->and_where_open()->or_where('date', '>', time())->or_where('date', '=', NULL)->and_where_close()->find_all();
            if (sizeof($bans)) {
                $this->error(__('Сообщение о блокировке по IP'));
            }
        }
    }

    private function ip($ip, $arr)
    {
        $_ip = explode('.', $ip);
        foreach ($arr AS $pos) {
            $_ip[$pos] = 'x';
        }
        $ip = implode('.', $_ip);
        return $ip;
    }

    public function reviewAction()
    {

        $name = Arr::get($this->post, 'name');
        $tel = Arr::get($this->post, 'tel');
        $rating = Arr::get($this->post, 'rating');
        $text = Arr::get($this->post, 'text');
        $lang = Arr::get($this->post, 'lang');

        if (!$name) {
            $this->error(__('Введенное имя слишком короткое!'));
        }
        if (!$tel) {
            $this->error(__('Не указан номер телефона!'));
        }
        if (!$rating) {
            $this->error(__('Необходимо указать рейтинг!'));
        }
        if (!$text) {
            $this->error(__('Нужно ввести отзыв!'));
        }

        $data = [];
        $data['name'] = $name;
        $data['tel'] = $tel;
        $data['rating'] = $rating;
        $data['text'] = $text;
        $data['ip'] = System::getRealIP();
        $data['created_at'] = time();

        $check = DB::select(array(DB::expr('COUNT(contacts.id)'), 'count'))->from('contacts')->where('ip', '=', Arr::get($data, 'ip'))->where('created_at', '>', time() - 60)->as_object()->execute()->current();
        if (is_object($check) AND $check->count) {
            $this->error(__('Частая отправка сообщений'));
        }

        // Save contact message to database
        $keys = array();
        $values = array();
        foreach ($data as $key => $value) {
            $keys[] = $key;
            $values[] = $value;
        }
        $lastID = DB::insert('contacts', $keys)->values($values)->execute();
        $lastID = Arr::get($lastID, 0);

        // Save log
        $qName = 'Сообщение из контактной формы';
        $url = '/wezom/contacts/edit/' . $lastID;
        Log::add($qName, $url, 2);

        // Send E-Mail to admin
        $mail = CommonI18n::factory('mail_templates')->getRowSimple(1, 'id', 1);
        if ($mail) {
            $from = array('{{site}}', '{{name}}', '{{email}}', '{{theme}}', '{{ip}}', '{{phone}}');
            $to = array(Arr::get($_SERVER, 'HTTP_HOST'), $name, $email, $theme, System::getRealIP(), $phone);
            $subject = str_replace($from, $to, $mail->subject);
            $text = str_replace($from, $to, $mail->text);
            Email::send($subject, $text);
        }

        // Пользователю
        $mail = CommonI18n::factory('mail_templates')->getRowSimple(36, 'id', 1);
        if ($mail) {
            $from = array('{{site}}', '{{name}}');
            $to = array(Arr::get($_SERVER, 'HTTP_HOST'), $name);
            $subject = str_replace($from, $to, $mail->subject);
            $text = str_replace($from, $to, $mail->text);
            Email::send($subject, $text, $email);
        }

        $this->success(__('Спасибо за сообщение!'));
    }

    public function priceAction()
    {
        $name = Arr::get($this->post, 'name');
        $country = Arr::get($this->post, 'country');
        $city = Arr::get($this->post, 'city');
        $confirm = Arr::get($this->post, 'confirm');
        $name = Arr::get($this->post, 'name');
        $name = Arr::get($this->post, 'name');
        $name = Arr::get($this->post, 'name');
        $name = Arr::get($this->post, 'name');


        $data = [];
        $data = [];
        $data = [];
        $data = [];
        $data = [];
        $data = [];
        $data = [];
        $data = [];
        $data = [];

        $keys = [];
        $values = [];
        foreach ($data as $key => $value) {
            $keys[] = $key;
            $values[] = $value;
        }

        $order_id = DB::insert('orders', $keys)->values($values)->execute();
    }

}
