<table class="table table-bordered table-hover">
<?php

require_once '../authentication/superadmin-class.php';
include_once __DIR__.'/../../../database/dbconfig2.php';

$user = new SUPERADMIN();
if(!$user->isUserLoggedIn())
{
 $user->redirect('../../../private/superadmin/');
}



function get_total_row($pdoConnect)
{

}

$total_record = get_total_row($pdoConnect);
$limit = '20';
$page = 1;
if(isset($_POST['page']))
{
  $start = (($_POST['page'] - 1) * $limit);
  $page = $_POST['page'];
}
else
{
  $start = 0;
}

$query = "
SELECT * FROM users WHERE user_type = :user_type
";
$output = '';
if($_POST['query'] != '')
{
  $query .= '
  AND first_name LIKE "%'.str_replace(' ', '%', $_POST['query']).'%"
  AND last_name LIKE "%'.str_replace(' ', '%', $_POST['query']).'%"
  AND middle_name LIKE "%'.str_replace(' ', '%', $_POST['query']).'%"
  AND email LIKE "%'.str_replace(' ', '%', $_POST['query']).'%"
  ';
}

$query .= 'ORDER BY id ASC ';

$filter_query = $query . 'LIMIT '.$start.', '.$limit.'';

$statement = $pdoConnect->prepare($query);
$statement->execute(array(":user_type" => 3));
$total_data = $statement->rowCount();

$statement = $pdoConnect->prepare($filter_query);
$statement->execute(array(":user_type" => 3));
$total_filter_data = $statement->rowCount();

if($total_data > 0)
{
$output = '

    <thead>
    <th>NAME</th>
    <th>PHONE NUMBER</th>
    <th>EMAIL</th>
    <th>ACTIONS</th>
    </thead>
';
  while($row=$statement->fetch(PDO::FETCH_ASSOC))
  {

    if ($row["account_status"] == "active") {
      $buttons = '<button type="button" class="btn btn-danger V"><a href="controller/user-controller?id='.$row["id"].'&disabled_alumni=1" class="delete"><i class="bx bxs-trash"></i></a></button>';
    
    } else if ($row["account_status"] == "disabled") {
      $buttons = '<button type="button" class="btn btn-warning V"><a href="controller/user-controller?id='.$row["id"].'&activate_alumni=1" class="activate">Activate</a></button>';
    }

    $output .= '
    <tr>
      <td>'.$row["last_name"].', '.$row["first_name"].' '.$row["middle_name"].'</td>
      <td>'.$row["phone_number"].'</td>
      <td>'.$row["email"].'</td>
      <td>
      '.$buttons.'
      </td>        
    </tr>
    ';
  }
}
else
{
  echo '<h1>No Data Found</h1>';
}

$output .= '
</table>
<div align="center">
  <ul class="pagination">
';

$total_links = ceil($total_data/$limit);
$previous_link = '';
$next_link = '';
$page_link = '';

//echo $total_links;

if($total_links > 5)
{
  if($page < 5)
  {
    for($count = 1; $count <= 5; $count++)
    {
      $page_array[] = $count;
    }
    $page_array[] = '...';
    $page_array[] = $total_links;
  }
  else
  {
    $end_limit = $total_links - 5;
    if($page > $end_limit)
    {
      $page_array[] = 1;
      $page_array[] = '...';
      for($count = $end_limit; $count <= $total_links; $count++)
      {
        $page_array[] = $count;
      }
    }
    else
    {
      $page_array[] = 1;
      $page_array[] = '...';
      for($count = $page - 1; $count <= $page + 1; $count++)
      {
        $page_array[] = $count;
      }
      $page_array[] = '...';
      $page_array[] = $total_links;
    }
  }
}
else
{
  $page_array[] = '...';
  for($count = 1; $count <= $total_links; $count++)
  {
    $page_array[] = $count;
  }
}

for($count = 0; $count < count($page_array); $count++)
{
  if($page == $page_array[$count])
  {
    $page_link .= '
    <li class="page-item active">
      <a class="page-link" href="#">'.$page_array[$count].' <span class="sr-only"></span></a>
    </li>
    ';

    $previous_id = $page_array[$count] - 1;
    if($previous_id > 0)
    {
      $previous_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$previous_id.'">Previous</a></li>';
    }
    else
    {
      $previous_link = '
      <li class="page-item disabled">
        <a class="page-link" href="#">Previous</a>
      </li>
      ';
    }
    $next_id = $page_array[$count] + 1;
    if($next_id > $total_links)
    {
      $next_link = '
      <li class="page-item disabled">
        <a class="page-link" href="#">Next</a>
      </li>
        ';
    }
    else
    {
      $next_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$next_id.'">Next</a></li>';
    }
  }
  else
  {
    if($page_array[$count] == '...')
    {
      $page_link .= '
      <li class="page-item disabled">
          <a class="page-link" href="#">...</a>
      </li>
      ';
    }
    else
    {
      $page_link .= '
      <li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a></li>
      ';
    }
  }
}

$output .= $previous_link . $page_link . $next_link;
$output .= '
  </ul>

</div>
';

echo $output;

?>

<script src="../../src/node_modules/sweetalert/dist/sweetalert.min.js"></script>
<script src="../../src/js/form.js"></script>

</table>