{extends file='tpl:comm.default'}

{block name='title'}
	修改联系信息
{/block}

{block name='body'}
<div class="text-notice">
	<p class="failure">{$errMsg}</p>
</div>
<div id="chname">
	<form action="{$CID}.{$PID}.{$BID}" method="post">
		<p>个性签名：</p>
		<p><input name="signature" value="{$signature|code}" /></p>
		<p>联系方式：</p>
		<p><input name="contact" value="{$contact|code}" /></p>
		<p><input type="submit" name="go" value="保存" /></p>
	</form>
</div>
{/block}
