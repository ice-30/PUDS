<center>
<?php if(!isset($_SESSION['T2SteamAuth'])){?>
	<center>
		<font size="5">
			Please <a href="<?php echo "?login&page=checkout&game={$pagegame}&server={$pageserver}&pid={$pagepackage}"; ?>"><img src="http://cdn.steamcommunity.com/public/images/signinthroughsteam/sits_small.png" /></a> so we could process your order.
		</font>
		
	</center>
<?php }else{

	$communityid = $_SESSION['T2SteamID64'];
	$authserver = bcsub($communityid, '76561197960265728') & 1;
	$authid = (bcsub($communityid, '76561197960265728')-$authserver)/2;
	$steamid = "STEAM_0:$authserver:$authid";
	
	$Steam64 = str_replace("http://steamcommunity.com/openid/id/", "", $_SESSION['T2SteamAuth']);
	
	
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_URL, "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=".STEAM_API."&steamids={$Steam64}"); 
	$data = curl_exec($ch); 
	curl_close($ch); 
	$Profile = json_decode($data)->response->players[0];

?>
	
	<table border="1">
	<tr>
	<td><b>Account Name</b></td>
	<td><?php echo $Profile->personaname; ?> (<a href="<?php echo "?logout&page=checkout&game={$pagegame}&server={$pageserver}&pid={$pagepackage}"; ?>">Logout</a>)</td>
	</tr>
	<tr>
	<td><b>SteamID</b></td>
	<td><?php echo $steamid; ?></td>
	</tr>
	<tr>
	<td><b>SteamID64</b></td>
	<td><a href="http://steamcommunity.com/profiles/<?php echo $communityid; ?>"><?php echo $communityid; ?></a></td>
	</tr>
	<td colspan=2>&nbsp</td>
	<tr>
	</tr>
	<tr>
	<td><b>Package</b></td>
	<td><?php echo $PACKAGES[$pid]["buytitle"]; ?></td>
	</tr>
	</tr>
	<tr>
	<td><b>Price</b></td>
	<td><?php echo price($pid,$steamid)." ".PAYPAL_CURRENCY; ?></td>
	
	</table>
	<br/>
	By submitting this order you agree to our <a href="javascript:window.open('tos.html','popUpWindow','height=200,width=400,left=10,top=10,resizable=no,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes');">Terms of Service</a>.
	<br/>
	<br/>
	<form action='<?php echo PAYPAL_URL; ?>' method='post' name='frmPayPal1'>
		<input type='hidden' name='business' value='<?php 
			if(PAYPAL_SANDBOX){
				echo PAYPAL_ID_SANDBOX;
			}else{
				if(array_key_exists('paypal_id',$PACKAGES[$pid])){
					echo $PACKAGES[$pid]['paypal_id'];
				}elseif(array_key_exists($pageserver,$SERVERS) && array_key_exists('paypal_id',$SERVERS[$pageserver])){
					echo $SERVERS[$pageserver]['paypal_id'];
				}else{
					echo PAYPAL_ID;
				}
			}
		?>'>
		<input type='hidden' name='cmd' value='_xclick'>

		<input type='hidden' name='item_name' value='<?php echo $PACKAGES[$pid]["buytitle"];?>'>
		<input type='hidden' name='item_number' value='<?php echo $pid;?>'>
		<input type='hidden' name='amount' value='<?php echo price($pid,$steamid);?>'>
		<input type='hidden' name='no_shipping' value='1'>
		<input type='hidden' name='currency_code' value='<?php echo PAYPAL_CURRENCY;?>'>
		<input type='hidden' name='handling' value='0'>
		<input type='hidden' name='custom' value='<?php echo $communityid;?>'>
		<input type='hidden' name='cancel_return' value='<?php echo DONATE_URL; ?>'>
		<input type='hidden' name='return' value='<?php echo DONATE_URL; ?>success.php'>
		<input type='hidden' name='notify_url' value='<?php echo DONATE_URL; ?>ipn.php'>

		<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_xpressCheckout.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	</form> 
<?php }?>
<br/>
</center>