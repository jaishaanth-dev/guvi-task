<?php
$connection = mysqli_connect("localhost","root","");
$db = mysqli_select_db($connection,'guvi');

if(isset($_POST['update']))
{

$email = $_POST['email'];


$query = "UPDATE users SET full_name='$_POST[full_name]',email='$_POST[email]',password='$_POST[password]'where email ='$_POST[email]' ";
$query_run = mysqli_query($connection,$query);

if($query_run)
{
echo " UPDATED SUCCESSFULLY!!!!";
}
else
{
echo "UPDATION FAILED ";
}


}
?>