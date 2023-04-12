<?php
function get_table_html($data) {
    global $mysqli;
    $rows = "";
    mysqli_data_seek($data, 0);
    while ($ticket = mysqli_fetch_assoc($data)){
        $total_ticket_messages = $mysqli->query("SELECT COUNT(*) FROM `ticket_messages` WHERE ticket_id=".$ticket['id']." ORDER BY `created_at` ASC")->fetch_assoc()['COUNT(*)'];
        $date = date_parse($ticket['created_at']);
        $rows .='
        <tr>
            <td>'. $ticket['display_id'] .'</td>
            <td>'. $ticket['order_number'] .'</td>
            <td>'. ($date['day']< 10 ? '0'.$date['day'] : $date['day']). "/".($date['month'] < 10 ? '0'.$date['month'] : $date['month']) .'</td>
            <td>'. $ticket['category'] .'</td>
            <td class="subject">'. $ticket['subject'] .'</td>
            <td>'. $total_ticket_messages .'</td>
            <td class="'. $ticket['status'] .' status">
                <p style="--colour: var(--colour-'. $ticket[ 'status' ] .');">'. $ticket['status'] .'</p>
            </td>
            <td>
                <div class="buttons">
                    <a href="/support/ticket.php?id='. $ticket['id'] .'" class="button">
                        <i class="fa-solid fa-up-right-from-square"></i>
                    </a>
                    <a class="button">
                        <i class="fa-solid fa-trash-can"></i>
                    </a>
                </div>
            </td>
        </tr>';
    }

    $html = '<table>
        <thead>
            <tr>
                <th>TicketID</th>
                <th>OrderID</th>
                <th>Date</th>
                <th>Category</th>
                <th>Subject</th>
                <th>Messages</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        '. $rows .'
        </tbody>
        </table>';

    return $html;
}
?>