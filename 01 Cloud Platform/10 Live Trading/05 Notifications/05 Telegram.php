<? include(DOCS_RESOURCES."/notifications/telegram-intro.html"); ?>

<p>Follow these steps to set up Telegram notifications in the deployment wizard:</p>

<ol>
    <li>On the Deploy Live page, enable at least one of the notification types.</li>
    <p>The following table shows the supported notification types:</p>

    <table class="table qc-table">
        <thead>
            <tr>
                <th>Notification Type</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Order Events</td>
                <td>Notifications for when the algorithm receives <code>OrderEvent</code> objects</td>
            </tr>
            <tr>
                <td>Insights</td>
                <td>Notifications for when the algorithm emits <code>Insight</code> objects</td>
            </tr>
        </tbody>
    </table>
    <li>Create a new Telegram group.</li>
    <li>Add a bot to your Telegram group.</li>
    <p>To create a bot, chat with @BotFather and follow its instructions. If you want to use our bot, the username is @quantconnect_notifications_bot.</p>
    <li>On the live deployment wizard, click <span class="button-name">Telegram</span>.</li>
    <li>Enter your user Id or group Id.</li>
    <p><? include(DOCS_RESOURCES."/notifications/telegram-group-id.html"); ?></p>
    <li>If you are not using our notification bot, enter the token of your bot.</li>
    <li>Click <span class="button-name">Add</span>.</li>
    <p>To add more Telegram notifications, click <span class="button-name">Add Notification</span> and then continue from step 2.</p>
</ol>
