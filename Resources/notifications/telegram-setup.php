<p>To send Telegram notifications in your code files, run:</p>
<div class="section-example-container">
    <pre class="csharp">Notify.Telegram(id, message, token);</pre>
    <pre class="python">self.notify.telegram(id, message, token)</pre>
</div>

<p>The <code>id</code> argument is your Telegram group Id. <? include(DOCS_RESOURCES."/notifications/telegram-group-id.html"); ?></p>

<p>The <code>token</code> optional argument is a token of a bot you must add to your Telegram group. To create a bot, chat with @BotFather and follow its instructions. If you want to use our bot, the username is @quantconnect_notifications_bot.</p>

<p>To add emoticons to your <code>message</code>, convert the emoticon character to UTF-32 with the <a href='https://www.branah.com/unicode-converter' rel='nofollow' target="_blank">Unicode Converter</a> on the Branah website. For example, the rocket emoji is <code>0001f680</code> in UTF-32 format, so use <code>\U001f680</code> in your message.</p>
