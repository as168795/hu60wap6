<?php
$tpl = $PAGE->start();
$USER->start();
$user = $USER;
$chat = new chat($USER);
if ($PAGE->ext[0]) {
    $roomname = $PAGE->ext[0];
    $tpl->assign('roomname', $roomname);
    $chat->checkName($roomname);

    if (isset($_GET['del'])) {
        try {
            $delId = (int)$_GET['del'];
            $chat->delete($delId);
        } catch (Exception $e) {
            $err_msg = $e->getMessage();
        }
    }

    if ($_POST['go']) {
        if (!$user->islogin)
            $err_msg = '你必须要<a href="user.login.' . $PAGE->bid . '">登录</a>才能发言';
        else {
            $token = new token($USER);
            $ok = $token->check($_POST['token']);
            if (!$ok) {
                $err_msg = '检测到重复发言，请先确认发言是否已经成功。';
            } else {
                $token->delete();

                $chat->checkroom($roomname);

                if ($_POST['content'] == '')
                    $err_msg = '内容不能为空';
                else {
                    $chat->chatsay($roomname, $_POST['content'], time());
                    //清空发言框的内容
                    $_POST['content'] = '';
                }
            }
        }
    }

    $ubbs = new ubbdisplay();
    $ubbs->setOpt('at.jsFunc', 'atAdd');
    $tpl->assign('err_msg', $err_msg);

    $chatCount = $chat->chatCount($roomname);
    $pageSize = isset($_GET['page_size']) ? min(max((int)$_GET['page_size'], 1), 200) : 15;
    $maxP = ceil($chatCount / $pageSize);

    if (isset($_GET['level'])) {
        $level = (int)$_GET['level'];
        $p = ceil(($chatCount - $level + 1) / $pageSize);
    } else {
        $p = (int)$_GET['p'];
    }

    if ($p < 1) {
        $p = 1;
    } else if ($p > $maxP) {
        $p = $maxP;
    }

    $offset = ($p - 1) * $pageSize;

	$startTime = isset($_GET['start_time']) ? (int)$_GET['start_time'] : null;
	$endTime = isset($_GET['end_time']) ? (int)$_GET['end_time'] : null;

    $list = $chat->chatList($roomname, $offset, $pageSize, $startTime, $endTime);

	// 审核检查
	$uinfo = new userinfo();
	foreach ($list as &$v) {
		$uinfo->uid($v['uid']);
		if ($v['review']) {
			$v['content'] = UbbParser::createPostNeedReviewNotice($USER, $uinfo, $v['id'], $v['content'], 'chat', true);
		}
	}

    $tpl->assign('list', $list);
    $tpl->assign('count', $chatCount);
    $tpl->assign('p', $p);
    $tpl->assign('maxP', $maxP);
    $tpl->assign('ubbs', $ubbs);
    $tpl->assign('chat', $chat);
    $tpl->assign('uinfo', $uinfo);

    if ($USER->islogin) {
        $token = new token($USER);
        $token->create();
        $tpl->assign('token', $token);
    }

    $tpl->display("tpl:chat");
} else {
    if ($_POST['roomname']) {
        $url = 'addin.chat.' . urlencode($_POST['roomname']) . '.' . $PAGE->bid;
        header("Location: $url");
        exit;
    }
    // 聊天室列表
    $list = $chat->roomlist();
    $tpl->assign('list', $list);
    
    $tpl->display("tpl:chat_list");
}
