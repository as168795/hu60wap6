{extends file='tpl:comm.default'}

{block name='title'}
	修改用户名
{/block}

{block name='body'}
<div class="text-notice">
	<p class="failure">{$errMsg}</p>
</div>
<div id="chname">
	<form action="{$CID}.{$PID}.{$BID}" method="post">
		<p>新用户名：</p>
		<p><input name="newName" value="{$smarty.post.newName|code}" /></p>
		<p><input type="submit" name="go" value="我要改名" /></p>
	</form>
</div>
{/block}
