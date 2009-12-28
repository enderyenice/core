<p align=justify>This section displays order placement statistics for today, this week and this month.</p>

<br>

<p>
<table border=0 cellpadding=0 cellspacing=0 width="80%">
<tr>
<td bgcolor=#dddddd>
    <table cellpadding=3 cellspacing=1 border=0 width="100%">
    <tr class=TableHead bgcolor=#ffffff>
        <th align=left>Status</th>
        <th>Today</th>
        <th>This week</th>
        <th>This month</th>
    </tr>
    <tr class="DialogBox">
        <td>Processed</td>
        <td align=center>{stat.processed.today}</td>
        <td align=center>{stat.processed.week}</td>
        <td align=center>{stat.processed.month}</td>
    </tr>
    <tr class="TableRow">
        <td>Queued</td>
        <td align=center>{stat.queued.today}</td>
        <td align=center>{stat.queued.week}</td>
        <td align=center>{stat.queued.month}</td>
    </tr>
    <tr class="DialogBox">
        <td>Failed/Declined</td>
        <td align=center>{stat.failed.today}</td>
        <td align=center>{stat.failed.week}</td>
        <td align=center>{stat.failed.month}</td>
    </tr>
    <tr class="TableRow">
        <td>Incomplete</td>
        <td align=center>{stat.not_finished.today}</td>
        <td align=center>{stat.not_finished.week}</td>
        <td align=center>{stat.not_finished.month}</td>
    </tr>
    <tr class="DialogBox">
        <td align=right><b>Gross total:</b></td>
        <td align=center>{price_format(stat.total.today):h}</td>
        <td align=center>{price_format(stat.total.week):h}</td>
        <td align=center>{price_format(stat.total.month):h}</td>
    </tr>
    <tr class="DialogBox">
        <td align=right><b>Payments received:</b></td>
        <td align=center>{price_format(stat.paid.today):h}</td>
        <td align=center>{price_format(stat.paid.week):h}</td>
        <td align=center>{price_format(stat.paid.month):h}</td>
    </tr>
    </table>
</td>
</tr>
</table>
</p>

<p>
<widget class="CButton" label="Perform order search" href="admin.php?target=order_list" font="FormButton">
