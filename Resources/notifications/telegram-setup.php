<p>To send Telegram notifications in your code files, run:</p>
<div class="section-example-container">
    <pre class="csharp">Notify.Telegram(id, message, token);</pre>
    <pre class="python">self.Notify.Telegram(id, message, token)</pre>
</div>

<p>The <code>id</code> argument is your Telegram user Id or group Id. <? include(DOCS_RESOURCES."/notifications/telegram-group-id.html"); ?></p>

<p>To add emoticons to your <code>message</code>, convert the emoticon character to UTF-32 with the <a href='https://www.branah.com/unicode-converter' rel='nofollow' target="_blank">Unicode Converter</a> on the Branah website. For example, the rocket emoji is <code>0001f680</code> in UTF-32 format, so use <code>\U001f680</code> in your message.</p>