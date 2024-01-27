<?php
    session_start();

    

    include 'includes/header.php';
    include 'classes/Friends.php';
    include 'classes/CRUD.php';
    include 'classes/Chat.php';

    $crud = new CRUD;
    $chat = new Chat($crud);

    $friendsDB = new Friends();

    $myid = $_SESSION['user_id'];
    $friends = $friendsDB->readFriends($myid);

    for($i = 0; $i<count($friends); $i++) {
        if($friends[$i]['id1'] === $myid) {
            unset($friends[$i]['id1']);
            unset($friends[$i]['fullname1']);
            unset($friends[$i]['pfp1']);
        } else {
            unset($friends[$i]['id2']);
            unset($friends[$i]['fullname2']);
            unset($friends[$i]['pfp2']);
        }
    }

?>

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<link rel="stylesheet" href="assets/css/chat.css">



<?php foreach($friends as $friend) : ?>
    <div class="chat-container">
        <div class="user-box">
            <a class="usr" id="<?=isset($friend['id1']) ? $friend['id1'] : $friend['id2']?>"><?=isset($friend['fullname1']) ? $friend['fullname1'] : $friend['fullname2']?></a>
            <div class="messages"></div>
            <form class="forma">
                <input name="inputi" class="inputi" type="text" placeholder="Type your message here" />
                <button>Send</button>
            </form>
        </div>
    </div>
<?php endforeach; ?>

<script>let connMap = new Map(); </script>

<?php foreach($friends as $friend) : ?>
    <script>
        document.getElementById('<?= isset($friend['id1']) ? $friend['id1'] : $friend['id2'] ?>').addEventListener('click', (e) => {
            let messages = e.target.parentNode.querySelector(".messages");
            messages.innerHTML = "";
            <?php $chatId = $chat->getChat($myid, isset($friend['id1']) ? $friend['id1'] : $friend['id2']); ?>

            let formaMsg = e.target.parentNode.querySelector(".forma");
            
            if(connMap.get("<?=$chatId?>") && formaMsg.style.display == 'flex') {
                connMap.get("<?=$chatId?>").close();
                connMap.delete("<?=$chatId?>");
                formaMsg.onsubmit = null;
                formaMsg.style.display = 'none';
                messages.style.border = 'none';
                messages.style.marginTop = '0';
                messages.style.paddingBlock = '0';
                return;
            }
            
            messages.style.marginTop = '10px';
            messages.style.border = '1px solid #ddd';
            messages.style.paddingBlock = '10px';

            <?php $messages = $crud->read("messages", ['chat_id' => $chatId]); ?>

            axios.get('readMessages.php?chat_id=<?=$chatId?>')
            .then(data => {
                data.data.forEach((message => {
                    var p = document.createElement('p');
                    p.innerHTML = `<b>${message['sender_id'] == <?=$_SESSION['user_id']?> ? '<?=$_SESSION['fullname']?>' : '<?= isset($friend['fullname1']) ? $friend['fullname1'] : $friend['fullname2'] ?>'}</b>: ${message['message']}`;
                    messages.appendChild(p);
                }));
                
                messages.scrollTop = messages.scrollHeight;
            });

            let conn = new WebSocket('ws://192.168.178.41:8080?chat_id=<?=$chatId?>');
            connMap.set("<?=$chatId?>", conn);

            conn.onopen = function(e) {
                console.log("Connection established!");
            };

            conn.onmessage = function(e) {
                let p = document.createElement('p');
                p.innerHTML = `<b><?= isset($friend['fullname1']) ? $friend['fullname1'] : $friend['fullname2'] ?></b> ${e.data}`;
                messages.appendChild(p);

                messages.scrollTop = messages.scrollHeight;
            };

            formaMsg.style.display = 'flex';

            formaMsg.onsubmit = (e) => {
                e.preventDefault();
                let p = document.createElement('p');
                p.innerHTML = `<b><?= $_SESSION['fullname'] ?></b> ${e.target.inputi.value}`;
                messages.appendChild(p);
                conn.send(JSON.stringify({mesazhi: e.target.inputi.value, chat_id: "<?=$chatId?>"}));

                axios.post('sendmessage.php', {
                    chatId: "<?=$chatId?>",
                    message: e.target.inputi.value,
                    senderId: "<?=$_SESSION['user_id']?>"
                })
                .then(data => console.log("done"));

                e.target.inputi.value = "";
                
                messages.scrollTop = messages.scrollHeight;
            };
        })
    </script>
<?php endforeach; ?>