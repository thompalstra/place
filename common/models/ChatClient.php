<?php
namespace common\models;

class ChatClient{

    public static $chatRoot = 'chat/';

    public $channel = 'default';

    const MAX_HISTORY = 5;

    public static $channelList = [
        'default',
        'gaming',
        'development,'
    ];

    public static $colors = [
        '#FF4545',
        '#3D8EB9',
        '#71BA51',
        '#B5DBC6',
        '#1DABB8',
        '#8870FF',
        '#EE543A',
        '#00587A',
        '#3B0102',
        '#27AE60',
        '#A42A15',
        '#282256',
    ];

    public function connect($data){
        $session = &\Frag::$app->session;

        if(!isset($session['chat']) || !isset($session['chat']['session_id'])){
            $chat = [];
            $chat['errors'] = [];
            // if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
            //     // valid
            //     unset($chat['errors']['email']);
            // } else {
            //     $chat['errors']['email'] = 'Invalid email';
            // }

            if(strlen($data['username']) < 5){
                // too short
                $chat['errors']['username'] = 'Username too short';
            }
            if(strlen($data['username']) > 25){
                // too long
                $chat['errors']['username'] = 'Username too long';
            }

            if(strlen($data['username']) <= 25 && strlen($data['username']) >= 5) {
                unset($chat['errors']['username']);
            }

            $chat['attr']['username'] = $data['username'];
            //$chat['attr']['email'] = $data['email'];


            if(empty($chat['errors'])){
                $guid = com_create_guid();
                $guid = substr($guid, 1, strlen($guid) - 2);
                $chat['session_id'] = $data['username'] . '_' . uniqid() . '_' . $guid;
                $chat['username'] = $data['username'];
                //$chat['email'] = $data['email'];
                $chat['color'] = self::$colors[array_rand(self::$colors)];
            }
            $session['chat'] = $chat;
        }
        return true;
    }

    public function disconnect(){
        $session = &\Frag::$app->session;
        if(isset($session['chat'])){
            unset($session['chat']);
        }
        return true;
    }

    public function submitMessage($post){
        $filePath = $this->getChat(date('Y'), date('m'), date('d'));
        $session = &\Frag::$app->session;
        $result = false;
        $errorMessage = '';
        if(isset($session['chat'])){
            $message = $post['message'];
            $message = trim($message);
            if(strlen($message) < 4 || strlen($message) > 255) {
                if(strlen($message) < 4){
                    $errorMessage = 'Message too short';
                } else if (strlen($message) > 255){
                    $errorMessage = 'Message too long';
                }
            } else {
                $line = $this->constructLine($message);
                $data = file_get_contents($filePath);
                $result = @file_put_contents($filePath, $line.$data);
                if($result){
                    $result = true;
                }
            }
        }
        return [
            'result' => $result,
            'message' => $errorMessage,
        ];
    }

    public function constructLine($message){
        $session = \Frag::$app->session;
        $color = $session['chat']['color'];
        $username = $session['chat']['username'];
        $line = "<span style='color: $color'>" . $username. '</span> <br>';
        $message = self::emoji($message);
        $line .= $message . "\r\n";

        return $line;
    }

    public static $emojiSearch = [
        ':)',
        ':(',
        ':|',
        ':@',
        'XD',
        ':P',
        ":'(",
        ":o",
        "cancer",
    ];

    public static $emojiReplace = [
        '<i class="em em-smile"></i>',
        '<i class="em em-frowning"></i>',
        '<i class="em em-expressionless"></i>',
        '<i class="em em-angry"></i>',
        '<i class="em em-laughing"></i>',
        '<i class="em em-stuck_out_tongue"></i>',
        '<i class="em em-sleepy"></i>',
        '<i class="em em-open_mouth"></i>',
        '<i class="em em-cancer"></i>',
    ];

    public static function emoji($message){
        return str_replace(self::$emojiSearch, self::$emojiReplace, $message);
    }

    public static function img($message){
        // match htt
        $m = "/img:([^\s]*)/";
        preg_match($m, $message, $search);
        if(isset($search[0]) && isset($search[1])){
            if(!empty($search[1])){
                $url = $search[1];
                $file_headers = @get_headers("$url");
                if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
                    $exists = false;
                }
                else {
                    $exists = true;
                }
                if($exists){
                    return preg_replace($m, "<img class='chat image' src='$url'/>", $message);
                } else {
                    return preg_replace($m, "image not found", $message);
                }
            }
        }
        // else {
        //     $s = "/img:https([^\s]*)/";
        //     preg_match($s, $message, $search);
        //
        //     if(isset($search[0]) && isset($search[1])){
        //         if(!empty($search[1])){
        //             $url = $search[1];
        //             $file_headers = @get_headers("http$url");
        //             if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
        //                 $exists = false;
        //             }
        //             else {
        //                 $exists = true;
        //             }
        //             if($exists){
        //                 return preg_replace($s, "<img class='chat image' src='http$url'/>", $message);
        //             } else {
        //                 return preg_replace($s, "image not found", $message);
        //             }
        //         }
        //     }
        // }
        return $message;
    }

    public function ui(){

        // var_dump($this->img("this is my message img:https://media1.giphy.com/media/3o7buibe1R5ea8WDsI/giphy.gif oh shit waddup"));
        // var_dump($this->img("img:http://www.gravatar.com/avatar/c910a4e6fcc5d9053caf9a11f3bb92ca?s=32&d=identicon&r=PG"));
        // var_dump($this->img("this is my message img:https://dsadsadsadsaadsa oh shit waddup"));
        // die();
        $session = &\Frag::$app->session;

        $out = $this->beginUI();


        if(isset($session['chat']) && empty($session['chat']['errors'])){

            $out .= "<div class='inner inner-container'>";
            $out .= "<div class='history load-more' offset='2'>load more</div>";
            $out .= "<div class='chat history-container'>";
            $out .= $this->readHistory(1);
            $out .= "</div>";

            $out .= "<div class='chat message-container'>";
            $out .= $this->messages();
            $out .= "</div>";
            $out .= "</div>";
            $out .= $this->createInput();
        } else {
            $out .= $this->createConnect();
        }

        $out .= $this->endUI();
        $this->js();
        return $out;
    }

    public function beginUI(){
        $session = \Frag::$app->session;
        $out = "";
        $out .= "<section class='chat chat-container'>";
        $out .= "<div class='toolbar'>";
        $out .= "<button class='chat-disconnect'>logout</button>";
        $out .= "</div>";

        return $out;
    }
    public function endUI(){
        return "</section>";
    }

    public function showErrors(){
        $session = \Frag::$app->session;
        $out = '';
        if(isset($session['chat']) && isset($session['chat']['errors'])){
            foreach($session['chat']['errors'] as $error){
                $out .= "<label class='label label-default alert'>" . $error . "</label>";
            }
        }
        return $out;
    }

    public function getChat($year, $month, $day){
        // return \Frag::$app->root.self::$chatRoot . \Frag::$app->session['chat']['session_id'] . '.txt';
        $year = date('Y');
        $month = date('m');
        $day = date('d');
        //return \Frag::$app->root.self::$chatRoot . \Frag::$app->session['chat']['session_id'] . '.txt';
        return \Frag::$app->root . self::$chatRoot . $this->channel . '/' . $year . '/' . $month . '/' . $day . '.txt';
    }

    public static $historyMessages = [
        "I’ve got a device to fetch futuristic herbs. It’s a thyme machine.",
        "Pleased to say I was voted “Most likely to travel back in time, Class of 2053”",
        "Time travel classes: Starts 1915.",
        "I’d take up time travel, but there’s no future in it.",
        "Won a prize in the local time travel club raffle. Two tickets to the 1966 World Cup final.",
        "Great Scot!<br>",
        "Knock Knock<br>
        Who is it?<br>
        A Time Traveler<br>
        A Time Traveler who?<br>
        Knock Knock",
    ];

    public static function getHistoryMessage(){
        return self::$historyMessages[array_rand(self::$historyMessages)];
    }


    public function state($filePath){

        $checkDir = dirname($filePath) . '/';
        if(!file_exists($checkDir)){
            mkdir($checkDir, 0777, true);
        }

        if(!file_exists($filePath)){
            //$data = "Start chat...\r\n";
            $fp = fopen($filePath, 'a');
            fwrite($fp, $data);
        }
    }

    public function readHistory($offset){
        if($offset > self::MAX_HISTORY){
            return;
        }
        $date = date('Y-m-d');
        $time = strtotime("$date -$offset days");
        $year = date('Y',  $time);
        $month = date('m', $time);
        $day = date('d', $time);
        $out = [];
        $filePath = \Frag::$app->root . self::$chatRoot . $this->channel . '/' . $year . '/' . $month . '/' . $day . '.txt';
        if(file_exists($filePath)){
            $handle = fopen($filePath, "r");
            if($handle){
                while (($line = fgets($handle)) !== false){
                    $out[] = $this->message($line);
                }
            }
            $d = date('D', $time) . ' ' . date('d', $time) . ' ' . date('M', $time) . ' ' . date('Y', $time);
            $out[]  = "<div class='chat outer'><div class='date line'>$d</div></div>";
            if($offset == self::MAX_HISTORY){
                $out[] = "<div class='chat outer'><div class='date line'>".self::getHistoryMessage()."</div></div>";
            }
            $out = array_reverse($out);
            $out = implode($out, ' ');
            set_time_limit(5);
            return $out;
        } else {
            $d = date('D', $time) . ' ' . date('d', $time) . ' ' . date('M', $time) . ' ' . date('Y', $time);
            $out = '';
            $out .= "<div class='chat outer'><div class='date line'>$d</div></div>";
            if($offset == self::MAX_HISTORY){
                $out .= "<div class='chat outer'><div class='date line'>".self::getHistoryMessage()."</div></div>";
            }
            return $out;
        }

    }

    public function readChat($filePath, $year, $month, $day, $first = true){
        set_time_limit(5);
        $out = false;
        $handle = fopen($filePath, "r");
        $i = 0;
        $deductedDays = -1;
        if($handle){
            $out = [];
            while (($line = fgets($handle)) !== false && $i < 100) {
                // process the line read.
                $out[] = $this->message($line);
                $i++;
            }
            $time = time();
            $d = date('D', $time) . ' ' . date('d', $time) . ' ' . date('M', $time) . ' ' . date('Y', $time);
            // $out[]  = "<div class='chat outer'><div class='date line'>$d</div></div>";
            $out[]  = "<div class='chat outer'><div class='date line'>Today</div></div>";
            fclose($handle);


            // if($i < 100 && $first == true){
            //     while($deductedDays > -30){
            //         $time = strtotime("$day-$month-$year $deductedDays days");
            //         $newYear = date('Y',  $time);
            //         $newMonth = date('m', $time);
            //         $newDay = date('d', $time);
            //         $newPath = \Frag::$app->root . self::$chatRoot . $this->channel . '/' . $newYear . '/' . $newMonth . '/' . $newDay . '.txt';
            //         $d = date('D', $time) . ' ' . date('d', $time) . ' ' . date('M', $time) . ' ' . date('Y', $time);
            //         if(file_exists($newPath)){
            //             $result = $this->readChat($newPath, $newYear, $newMonth, $newDay, false);
            //             $i = $i + count($result);
            //             $out = array_merge($out, $result);
            //         }
            //         $out[]  = "<div class='chat outer'><div class='date line'>$d</div></div>";
            //         $deductedDays--;
            //     }
            // }
        }
        return $out;
    }

    public function messages(){
        $session = &\Frag::$app->session;

        $filePath = $this->getChat(date('Y'), date('m'), date('d'));
        $this->state($filePath);
        $i = 0;
        $out = [];
        //if ($handle) {
            $out = $this->readChat($filePath, date('Y'), date('m'), date('d'));
            // while (($line = fgets($handle)) !== false && $i < 100) {
            //     // process the line read.
            //     $out[] = $this->message($line);
            //     $i++;
            // }
            // fclose($handle);
        //} else {
            // error opening the file.
        //}
        $out = array_reverse($out);

        $out = implode($out, ' ');
        return $out;
    }

    public function message($line){
        $out = "<div class='chat outer'><div class='chat line'>";
        $out .= $line;
        $out .= "</div></div>";
        return $out;
    }

    public function createInput(){
        $out = "<div class='connect-container'>";
        $out .= "<div class='chat-warning'>This is a message</div>";
        $out .= "<input type='text' placeholder='message' class='chat-message pull-left'>";
        $out .= "<button class='chat-send pull-right'><i class='material-icons'>send</i></button>";
        $out .= "</div>";
        return $out;
    }

    public function createConnect(){
        $session = \Frag::$app->session;
        $out = "<div class='connect-container'>";

        if(!empty($session['chat']['errors'])){
            $out .= self::showErrors();
        }

        if(isset($session['chat']) && isset($session['chat']['attr'])){
            //$mailVal = $session['chat']['attr']['email'];
            $nameVal = $session['chat']['attr']['username'];
        } else {
            $mailVal = '';
            $nameVal = '';
        }

        //$out .= "<input type='text' placeholder='email' class='chat-email' value='$mailVal'>";
        $out .= "<input type='text' placeholder='username' class='chat-name' value='$nameVal'>";
        $out .= "<button class='chat-connect pull-right'><i class='material-icons'>input</i></button>";
        $out .= "</div>";
        return $out;
    }

    public function js(){
        $maxHistory = self::MAX_HISTORY;
$js = <<<JS
function ChatClient(){
    this.container = f('.chat.chat-container');
    //this.messageContainer = f('.chat.message-container');
    this.messageContainer = f('.chat.chat-container > .inner.inner-container');
    this.interval = null;
    this.scrollTimeout = null;
    this.oldScroll;
    this.isScrollBottom = true;
    this.channel = "$this->channel";
    this.maxHistoryOffset = "$maxHistory"
    this.send = function(e){

    }
    this.connect = function(e){

    }
    this.disconnect = function(e){

    }
    this.refresh = function(e){

    }

    this.setInterval = function(){
        // setInterval(function(e){
        //     height = parseInt(chat.messageContainer.style('height'));
        //     scrollHeight = chat.messageContainer[0].scrollHeight - height;
        //     scrollTop = chat.messageContainer[0].scrollTop;
        //
        //     this.isScrollBottom = (scrollHeight == scrollTop);
        //     f.reload({
        //         container: '.chat.message-container',
        //         done: function(e){
        //             chat.messageContainer = f('.chat.chat-container > .inner.inner-container');
        //             if(this.isScrollBottom == true){
        //                 chat.messageContainer[0].scrollTop = chat.messageContainer[0].scrollHeight;
        //             } else {
        //                 chat.messageContainer[0].scrollTop = chat.oldScroll;
        //             }
        //             chat.messageContainer[0].onscroll = scrollEvent;
        //         }
        //     });
        // }, 2000);
    }

    scrollEvent = function(){
        chat.oldScroll = this.scrollTop;

        if(this.scrollTop == 0){
            f('.history.load-more').show();
        } else {
            f('.history.load-more').hide();
        }
    }

    if(this.messageContainer.exists()){
        elem = this.messageContainer[0];
        elem.scrollTop = elem.scrollHeight;
        elem.onscroll = scrollEvent;
    }

    f(document).on('click', '.history.load-more', function(e){
        elem = f(this);
        offset = elem.attr('offset');
        f.request.send({
            url: '/chat/history',
            method: 'post',
            type: 'document',
            data: {
                channel: chat.channel,
                offset: offset,
            },
            done: function(response){
                if(elem.attr('offset') >= chat.maxHistoryOffset){
                    elem.style('display', 'none');
                } else {
                    elem.attr('offset', parseInt(offset) + 1);
                }
                children = f(response).findOne('body').children();
                children.each(function(e){
                    if(typeof this == 'object'){
                        f('.chat.history-container')[0].insertBefore(this, f('.chat.history-container')[0].firstChild);
                    }
                });
            }
        })
    });

    f(document).on('click', '.chat.image', function(e){
        src = f(this).attr('src');

        var win = window.open(src, '_blank');
        win.focus();
    });

    f(document).on('click', '.chat-container .chat-connect', function(e){
        e.preventDefault();
        p = this.parentNode;
        //email = f(p).findOne('.chat-email')[0].value;
        username = f(p).findOne('.chat-name')[0].value;
        f.request.send({
            method: 'POST',
            type: 'json',
            url: '/chat/connect',
            data: {
                channel: chat.channel,
                username: username,
                //email: email,
            },
            done: function(resp){
                f.reload({
                    container: '.chat.chat-container',
                    done: function(e){
                        elem = f('.chat.message-container')[0];
                        elem.scrollTop = elem.scrollHeight;
                    }
                });
            }
        })
    });

    f(document).on('click', '.chat-container .chat-send', function(e){
        e.preventDefault();
        p = this.parentNode;
        message = f(p).findOne('.chat-message')[0].value;
        f('.chat-warning').removeClass('warning');
        f.request.send({
            method: 'POST',
            type: 'json',
            url: '/chat/message',
            data: {
                channel: chat.channel,
                message: message
            },
            done: function(resp){
                if(resp.result == true){

                    f.reload({
                        container: '.chat.message-container',
                        done: function(e){
                            f(p).findOne('.chat-message')[0].value = '';
                            elem = f('.inner.inner-container')[0];
                            elem.scrollTop = elem.scrollHeight;
                            chat.oldScroll = elem.scrollTop;
                            f('.chat-message')[0].focus();
                        }
                    });
                } else {
                    f('.chat-warning').addClass('warning');
                    f('.chat-warning')[0].innerHTML = resp.message;
                }

            }
        })
    });
    f(document).on('keydown', '.chat-container .chat-message', function(e){
        if(e.keyCode == 13){
            e.preventDefault();
            send = f(this).siblings('.chat-send');
            send.trigger('click');
        }
    });

    f(document).on('click', '.chat-container .chat-disconnect', function(e){
        f.request.send({
            method: 'POST',
            type: 'JSON',
            url: '/chat/disconnect',
            done: function(resp){
                f.reload({
                    container: '.chat.chat-container',
                    done: function(e){
                        elem = f('.chat.message-container')[0];
                        elem.scrollTop = elem.scrollHeight;
                    }
                });
            }
        })
    });

    this.setInterval();

};
var chat = new ChatClient();



JS;
\Frag::$app->view->registerJs($js);
    }
}


?>
