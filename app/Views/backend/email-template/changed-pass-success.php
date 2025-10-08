<h3>Xin chao <?= $mailData['user']->name ?></h3>
<p>
    Password tài khoan cua ban da duoc thay doi.<br>
    Day la thong tin ban can luu lai : <br>
    <b>Login ID:</b> <?= $mailData['user']->username ?> or <?= $mailData['user']->email ?><br>
    <b>New Password:</b> <?= $mailData['password'] ?>
</p>