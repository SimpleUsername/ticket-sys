<h1>События</h1>

<div class="table-responsive">
    <table>
        .
        <tr><td>Год</td><td>Проект</td><td>Описание</td></tr>
        <?php

        foreach($data as $row)
        {
            echo '<tr><td>'.$row['Year'].'</td><td>'.$row['Site'].'</td><td>'.$row['Description'].'</td></tr>';
        }

        ?>
    </table>
    <? echo "<pre>"; print_r($data); echo "</pre>";?>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>Header</th>
            <th>Header</th>
            <th>Header</th>
            <th>Header</th>
        </tr>
        </thead>
        <tbody>
        <? for($i = 0 ; $i < 4 ; $i++) {?>
            <tr>
                <td>1,001</td>
                <td>Lorem</td>
                <td>ipsum</td>
                <td>dolor</td>
                <td>sit</td>
            </tr>
        <? } ?>
        </tbody>
    </table>
</div>